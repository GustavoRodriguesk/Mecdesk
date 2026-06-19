<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{

    protected $fillable = [
        'nome_fantasia',
        'razao_social',
        'cnpj',
        'email',
        'telefone',
        'whatsapp',
        'cep',
        'logradouro',
        'numero',
        'bairro',
        'cidade',
        'estado',
        'logo',
        'plano',
        'ativo',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }

    public function veiculos()
    {
        return $this->hasMany(Veiculo::class);
    }

    public function pecas()
    {
        return $this->hasMany(Peca::class);
    }

    public function servicos()
    {
        return $this->hasMany(Servico::class);
    }

    public function ordens()
    {
        return $this->hasMany(OrdemServico::class);
    }
}
