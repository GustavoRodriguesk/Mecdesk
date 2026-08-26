<?php

namespace App\Http\Controllers;

use App\Events\AssinaturaAtivada;
use App\Models\Assinatura;
use App\Models\Empresa;
use App\Models\Plano;
use App\Models\User;
use App\Services\MercadoPago\MercadoPagoService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class CheckoutController extends Controller
{
    public function __construct(
        protected MercadoPagoService $mpService
    ) {}

    /**
     * Exibe a página unificada de contratação do MecDesk (3 etapas).
     */
    public function contratar(Request $request)
    {
        $planoPro = Plano::where('slug', 'pro')->where('ativo', true)->firstOrFail();
        $amount = (float) $planoPro->preco_mensal;
        $publicKey = config('mercadopago.public_key');

        $user = auth()->user();
        $initialStep = 1;
        $empresa = null;
        $assinatura = null;

        if ($user) {
            $empresa = $user->empresa;
            if ($empresa && $empresa->isAtiva()) {
                return redirect()->route('dashboard');
            }

            if ($empresa) {
                $assinatura = Assinatura::firstOrCreate(
                    ['empresa_id' => $empresa->id],
                    [
                        'plano_id' => $planoPro->id,
                        'metodo_pagamento' => 'cartao',
                        'status' => 'pending',
                        'preco_contratado' => $amount,
                    ]
                );
                $assinatura->update([
                    'plano_id' => $planoPro->id,
                    'preco_contratado' => $amount,
                ]);
                $empresa->update(['plano_id' => $planoPro->id]);
            }

            $initialStep = 2;
        }

        return view('planos.contratar', [
            'plano' => $planoPro,
            'amount' => $amount,
            'publicKey' => $publicKey,
            'initialStep' => $initialStep,
            'user' => $user,
            'empresa' => $empresa,
            'assinatura' => $assinatura,
        ]);
    }

    /**
     * Cria a conta do usuário e da oficina na Etapa 1 do fluxo de contratação.
     */
    public function cadastrarConta(Request $request): JsonResponse
    {
        if (Auth::check()) {
            $user = Auth::user();

            return response()->json([
                'success' => true,
                'message' => 'Usuário já autenticado.',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'empresa' => [
                    'id' => $user->empresa?->id,
                    'nome_fantasia' => $user->empresa?->nome_fantasia,
                ],
            ]);
        }

        $validated = $request->validate([
            'empresa' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'telefone' => ['nullable', 'string', 'max:50'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ], [
            'empresa.required' => 'O nome da oficina é obrigatório.',
            'name.required' => 'O seu nome completo é obrigatório.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Informe um endereço de e-mail válido.',
            'email.unique' => 'Este e-mail já está cadastrado em nosso sistema.',
            'password.required' => 'A senha é obrigatória.',
            'password.confirmed' => 'A confirmação de senha não confere.',
        ]);

        $plano = Plano::where('slug', 'pro')->where('ativo', true)->firstOrFail();
        $user = null;
        $empresa = null;

        DB::transaction(function () use ($validated, $plano, &$user, &$empresa) {
            $empresa = new Empresa([
                'nome_fantasia' => $validated['empresa'],
                'email' => $validated['email'],
                'telefone' => $validated['telefone'] ?? null,
                'plano_id' => $plano->id,
            ]);
            $empresa->ativo = false;
            $empresa->save();

            Assinatura::create([
                'empresa_id' => $empresa->id,
                'plano_id' => $plano->id,
                'metodo_pagamento' => 'cartao',
                'status' => 'pending',
                'preco_contratado' => $plano->preco_mensal,
            ]);

            $user = User::create([
                'empresa_id' => $empresa->id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'admin',
            ]);
        });

        event(new Registered($user));
        Auth::login($user);

        return response()->json([
            'success' => true,
            'message' => 'Conta criada com sucesso!',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'empresa' => [
                'id' => $empresa->id,
                'nome_fantasia' => $empresa->nome_fantasia,
            ],
        ], 201);
    }

    /**
     * Exibe a tela de checkout para contratação do Plano Pro (R$ 99,90/mês).
     */
    public function show(?Plano $plano = null, ?Request $request = null)
    {
        return $this->contratar($request ?? request());
    }

    /**
     * Processa a criação ou atualização da assinatura recorrente no Mercado Pago (/preapproval).
     */
    public function processarPagamento(Request $request): JsonResponse
    {
        $request->validate([
            'card_token_id' => 'required|string',
            'idempotency_key' => 'nullable|string',
        ]);

        $user = auth()->user();
        $empresa = $user->empresa;

        // Proteção contra duplicidade no backend: bloqueia se a empresa já estiver ativa com assinatura authorized
        $assinaturaAtiva = $empresa->assinaturas()->where('status', 'authorized')->first();
        if ($assinaturaAtiva && $empresa->isAtiva()) {
            return response()->json([
                'error' => 'Assinatura já ativa',
                'message' => 'Sua empresa já possui uma assinatura ativa no Plano Pro.',
            ], 422);
        }

        // Obtém o plano Pro ativo no servidor (Zero-Trust: valor indiscutível)
        $plano = Plano::where('slug', 'pro')->where('ativo', true)->firstOrFail();
        $cardTokenId = $request->input('card_token_id');

        $rawIdempotencyKey = $request->input('idempotency_key');
        $idempotencyKey = (! empty($rawIdempotencyKey) && Str::isUuid($rawIdempotencyKey))
            ? $rawIdempotencyKey
            : (string) Str::uuid();

        // Localiza a assinatura existente da empresa
        $assinatura = $empresa->assinaturas()->latest()->first();

        try {
            // Se já existir mp_preapproval_id em status pending, atualiza o cartão via PUT /preapproval/{id} para evitar contrato duplicado
            if ($assinatura && $assinatura->mp_preapproval_id && $assinatura->status === 'pending') {
                $resultado = $this->mpService->atualizarAssinatura($assinatura->mp_preapproval_id, $cardTokenId);
            } else {
                $resultado = $this->mpService->criarAssinatura($empresa, $user, $cardTokenId, $idempotencyKey);
            }

            $mpPreapprovalId = (string) ($resultado['id'] ?? $assinatura?->mp_preapproval_id ?? '');
            $status = $resultado['status'] ?? 'pending';

            // Tratamento de Rejeição Síncrona (Cartão inválido, sem limite, etc.)
            if ($status === 'rejected') {
                return response()->json([
                    'error' => 'Cartão recusado',
                    'message' => 'O pagamento não foi autorizado pelo seu cartão. Por favor, verifique os dados ou tente outro cartão.',
                    'status' => 'rejected',
                ], 422);
            }

            $dbStatus = in_array($status, ['authorized', 'pending', 'paused', 'cancelled', 'overdue', 'expired'], true) ? $status : 'pending';

            if (! $assinatura) {
                $assinatura = Assinatura::create([
                    'empresa_id' => $empresa->id,
                    'plano_id' => $plano->id,
                    'metodo_pagamento' => 'cartao',
                    'status' => $dbStatus,
                    'mp_preapproval_id' => $mpPreapprovalId ?: null,
                    'preco_contratado' => $plano->preco_mensal,
                ]);
            } else {
                $assinatura->update([
                    'plano_id' => $plano->id,
                    'metodo_pagamento' => 'cartao',
                    'status' => $dbStatus,
                    'mp_preapproval_id' => $mpPreapprovalId ?: $assinatura->mp_preapproval_id,
                    'preco_contratado' => $plano->preco_mensal,
                ]);
            }

            // Se aprovado/autorizado síncronamente, ativa a empresa e o acesso imediatamente
            if ($status === 'authorized') {
                $assinatura->update([
                    'status' => 'authorized',
                    'data_inicio' => $assinatura->data_inicio ?? now(),
                    'proximo_vencimento' => now()->addMonth(),
                    'valido_ate' => now()->addMonth(),
                ]);

                $empresa->plano_id = $plano->id;
                $empresa->ativo = true;
                $empresa->save();

                AssinaturaAtivada::dispatch($assinatura);
            }

            return response()->json($resultado, 200);

        } catch (\Throwable $e) {
            Log::error('Erro ao processar assinatura no Mercado Pago: '.$e->getMessage(), [
                'user_id' => $user->id,
                'empresa_id' => $empresa->id,
            ]);

            return response()->json([
                'error' => 'Falha no processamento da assinatura',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Callback de retorno após a conclusão do fluxo no checkout.
     */
    public function callback(Request $request)
    {
        $user = auth()->user();
        $empresa = $user->empresa;

        $empresa->refresh();

        if ($empresa->isAtiva()) {
            return redirect()->route('assinatura.sucesso');
        }

        return redirect()->route('assinatura.pendente')->with(
            'info',
            'Sua assinatura está em processamento. Assim que o Mercado Pago confirmar a autorização, seu acesso será liberado automaticamente.'
        );
    }
}
