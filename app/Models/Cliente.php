<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
        use HasFactory;

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
public function ordensServico()
{
    return $this->hasMany(OrdemServico::class);
}
}
