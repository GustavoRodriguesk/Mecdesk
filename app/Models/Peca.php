<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peca extends Model
{
    protected $fillable = [
    'nome',
    'codigo',
    'estoque',
    'valor_unitario',
];
}
