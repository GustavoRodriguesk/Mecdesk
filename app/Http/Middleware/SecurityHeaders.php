<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Injeta cabeçalhos de segurança essenciais nas respostas HTTP.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        // Prevenção contra Clickjacking
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Prevenção contra MIME-type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Proteção de Referrer
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Desativação de permissões desnecessárias do hardware
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // HSTS em conexões seguras
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
