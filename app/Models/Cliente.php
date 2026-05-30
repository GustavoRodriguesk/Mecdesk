<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
    'nome',
    'cpf_cnpj',
    'telefone',
    'email',
    'endereco'
];
public function veiculos()
{
    return $this->hasMany(Veiculo::class);
}
}
