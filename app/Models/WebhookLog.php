<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookLog extends Model
{
    use HasFactory;

    protected $table = 'webhook_logs';

    protected $fillable = [
        'event_id',
        'action',
        'resource_id',
        'payload',
        'signature',
        'processed',
        'error',
    ];

    protected function casts(): array
    {
        return [
            'payload'   => 'array',
            'processed' => 'boolean',
        ];
    }
}
