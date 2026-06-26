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

        $ordem->load(['cliente', 'veiculo', 'itens', 'empresa']);

        return view('aprovacao.show', compact('ordem'));
    }

    /**
     * Aprova a Ordem de Serviço.
     */
    public function approve(string $token, Request $request)
    {
        $ordem = $this->findByToken($token);

        if ($ordem->approval_status !== 'pending') {
            return redirect()
                ->route('aprovacao.show', $token)
                ->with('error', 'Esta Ordem de Serviço já foi respondida.');
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
    }

    /**
     * Reprova a Ordem de Serviço.
     */
    public function reject(string $token, Request $request)
    {
        $ordem = $this->findByToken($token);

        if ($ordem->approval_status !== 'pending') {
            return redirect()
                ->route('aprovacao.show', $token)
                ->with('error', 'Esta Ordem de Serviço já foi respondida.');
        }

        $request->validate([
            'approval_comment' => 'required|string|max:1000',
        ], [
            'approval_comment.required' => 'Informe o motivo da reprovação.',
            'approval_comment.max'      => 'O motivo deve ter no máximo 1000 caracteres.',
        ]);

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
    }

    /**
     * Busca a OS pelo token (sem EmpresaScope).
     */
    private function findByToken(string $token): OrdemServico
    {
        return OrdemServico::withoutGlobalScope(EmpresaScope::class)
            ->where('approval_token', $token)
            ->firstOrFail();
    }
}
