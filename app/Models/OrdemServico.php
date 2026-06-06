<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrdemServico extends Model
{
    use HasFactory;
    protected $table = 'ordem_servicos';
   protected $fillable = [
    'numero_os',
    'cliente_id',
    'veiculo_id',
    'user_id',
    'status',
    'descricao_problema',
    'observacoes',
    'valor_total',
    'aprovado_cliente',
    'data_entrada',
    'data_saida',
];
 

public function getStatusFormatadoAttribute()
{
    return match ($this->status) {
        'aberta' => 'Aberta',
        'em_andamento' => 'Em andamento',
        'aguardando_aprovacao' => 'Aguardando aprovação',
        'concluida' => 'Concluída',
        'cancelada' => 'Cancelada',
        default => $this->status,
    };
}

public function getStatusColorAttribute()
{
    return match ($this->status) {
        'aberta' => 'bg-blue-100 text-blue-800',
        'em_andamento' => 'bg-yellow-100 text-yellow-800',
        'aguardando_aprovacao' => 'bg-orange-100 text-orange-800',
        'concluida' => 'bg-green-100 text-green-800',
        'cancelada' => 'bg-red-100 text-red-800',
        default => 'bg-gray-100 text-gray-800',
    };
}
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class);
    }
    public function itens()
    {
        return $this->hasMany(OrdemServicoItem::class);
    }
    
}