<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Peca extends Model
{
    use HasFactory;
    protected $fillable = [
    'nome',
    'codigo',
    'estoque',
    'valor_unitario',
];
}
