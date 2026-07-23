<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Agendamento diário para geração de cobranças PIX recorrentes
Schedule::command('mecdesk:renovar-assinaturas-pix')->dailyAt('06:00');
