<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmpresaAtiva
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $empresa = $user->empresa;

        if (!$empresa) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Nenhuma empresa associada a esta conta.');
        }

        // Se a empresa não estiver ativa ou a assinatura expirou
        if (!$empresa->isAtiva()) {
            // Permite acessar rotas de logout, suporte, pendência de assinatura e perfil
            if ($request->routeIs(['logout', 'assinatura.pendente', 'planos.*', 'checkout.*', 'profile.*'])) {
                return $next($request);
            }

            return redirect()->route('assinatura.pendente');
        }

        return $next($request);
    }
}
