<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Credenciais do Mercado Pago
    |--------------------------------------------------------------------------
    |
    | Todas as chaves secretas e tokens de acesso são mantidos estritamente
    | em variáveis de ambiente (.env), prevenindo exposição acidental.
    |
    */

    'access_token' => env('MERCADOPAGO_ACCESS_TOKEN'),

    'public_key' => env('MERCADOPAGO_PUBLIC_KEY'),

    'webhook_secret' => env('MERCADOPAGO_WEBHOOK_SECRET'),

    'back_url' => env('MERCADOPAGO_BACK_URL', env('APP_URL') . '/planos/callback'),

];
