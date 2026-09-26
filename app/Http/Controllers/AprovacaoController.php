<?php

namespace App\Http\Controllers;

use App\Models\OrdemServico;
use App\Models\Scopes\EmpresaScope;
use Illuminate\Http\Request;

class AprovacaoController extends Controller
{
    /**
     * Exibe a página pública de aprovação da OS.
     */
    public function show(string $token)
    {
        $ordem = $this->findByToken($token);

        $ordem->load(['cliente', 'veiculo', 'itens', 'fotos', 'empresa']);

        $expirado = $this->isTokenExpired($ordem);

        return view('aprovacao.show', compact('ordem', 'expirado'));
    }

    /**
     * Aprova a Ordem de Serviço.
     */
    public function approve(string $token, Request $request)
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($token, $request) {
            $ordem = OrdemServico::withoutGlobalScope(EmpresaScope::class)
                ->where('approval_token', $token)
                ->lockForUpdate()
                ->firstOrFail();

            if ($this->isTokenExpired($ordem) && $ordem->approval_status === 'pending') {
                return redirect()
                    ->route('aprovacao.show', $token)
                    ->with('error', 'Este link de aprovação expirou. Solicite um novo link à oficina.');
            }

            if ($ordem->approval_status !== 'pending') {
                return redirect()
                    ->route('aprovacao.show', $token)
                    ->with('error', 'Esta Ordem de Serviço já foi respondida.');
            }

            if ($ordem->status !== 'aguardando_aprovacao') {
                return redirect()
                    ->route('aprovacao.show', $token)
                    ->with('error', 'Esta Ordem de Serviço não está aguardando aprovação no momento.');
            }

            $ordem->update([
                'approval_status'      => 'approved',
                'approval_response_at' => now(),
                'approval_ip'          => $request->ip(),
                'approval_user_agent'  => $request->userAgent(),
                'status'               => 'aprovada',
            ]);

            $ordem->historicos()->create([
                'status' => 'aprovada',
            ]);

            return redirect()
                ->route('aprovacao.show', $token)
                ->with('success', 'Ordem de Serviço aprovada com sucesso!');
        });
    }

    /**
     * Reprova a Ordem de Serviço.
     */
    public function reject(string $token, Request $request)
    {
        $request->validate([
            'approval_comment' => 'required|string|max:1000',
        ], [
            'approval_comment.required' => 'Informe o motivo da reprovação.',
            'approval_comment.max'      => 'O motivo deve ter no máximo 1000 caracteres.',
        ]);

        return \Illuminate\Support\Facades\DB::transaction(function () use ($token, $request) {
            $ordem = OrdemServico::withoutGlobalScope(EmpresaScope::class)
                ->where('approval_token', $token)
                ->lockForUpdate()
                ->firstOrFail();

            if ($this->isTokenExpired($ordem) && $ordem->approval_status === 'pending') {
                return redirect()
                    ->route('aprovacao.show', $token)
                    ->with('error', 'Este link de aprovação expirou. Solicite um novo link à oficina.');
            }

            if ($ordem->approval_status !== 'pending') {
                return redirect()
                    ->route('aprovacao.show', $token)
                    ->with('error', 'Esta Ordem de Serviço já foi respondida.');
            }

            if ($ordem->status !== 'aguardando_aprovacao') {
                return redirect()
                    ->route('aprovacao.show', $token)
                    ->with('error', 'Esta Ordem de Serviço não está aguardando aprovação no momento.');
            }

            $ordem->update([
                'approval_status'      => 'rejected',
                'approval_comment'     => $request->input('approval_comment'),
                'approval_response_at' => now(),
                'approval_ip'          => $request->ip(),
                'approval_user_agent'  => $request->userAgent(),
                'status'               => 'reprovada',
            ]);

            $ordem->historicos()->create([
                'status' => 'reprovada',
            ]);

            return redirect()
                ->route('aprovacao.show', $token)
                ->with('success', 'Ordem de Serviço reprovada. Obrigado pelo retorno!');
        });
    }

    /**
     * Busca a OS pelo token (sem EmpresaScope).
     */
    private function findByToken(string $token): OrdemServico
    {
        return OrdemServico::withoutGlobalScope(EmpresaScope::class)
            ->whereNotNull('approval_token')
            ->where('approval_token', $token)
            ->firstOrFail();
    }

    /**
     * Verifica se o token de aprovação expirou (validade: 15 dias).
     */
    protected function isTokenExpired(OrdemServico $ordem): bool
    {
        if (! $ordem->approval_requested_at) {
            return false;
        }

        return $ordem->approval_requested_at->addDays(15)->isPast();
    }
}
