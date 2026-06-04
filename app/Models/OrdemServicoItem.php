<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class OrdemServicoItem extends Model
{
    protected $table = 'ordem_servico_itens';

    protected $fillable = [
        'ordem_servico_id',
        'tipo_item',
        'peca_id',
        'servico_id',
        'descricao',
        'quantidade',
        'valor_unitario',
        'valor_total',
    ];

        public function peca()
        {
            return $this->belongsTo(Peca::class);
        }

        public function servico()
    {
        return $this->belongsTo(Servico::class);
    }

    public function ordem()
    {
        return $this->belongsTo(OrdemServico::class);
    }
}