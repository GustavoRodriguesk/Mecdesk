<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdemServicoHistorico extends Model
{
    protected $fillable = [
        'ordem_servico_id',
        'status'
    ];
    
}