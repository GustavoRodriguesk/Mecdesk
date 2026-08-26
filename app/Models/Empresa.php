<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresas';

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
        'plano_id',
        // ATENÇÃO: 'ativo' foi removido intencionalmente do fillable por motivos de segurança.
    ];

    protected function casts(): array
    {
        return [
            'ativo' => 'boolean',
        ];
    }

    public function plano()
    {
        return $this->belongsTo(Plano::class, 'plano_id');
    }

    /**
     * Accessor para resolver a colisão de nomes entre a coluna de banco 'plano' (string legada)
     * e a relação Eloquent plano() (belongsTo Plano::class).
     */
    public function getPlanoAttribute()
    {
        if ($this->relationLoaded('plano')) {
            return $this->getRelation('plano');
        }

        if (!empty($this->attributes['plano_id'])) {
            $planoModel = Plano::find($this->attributes['plano_id']);
            if ($planoModel) {
                return $planoModel;
            }
        }

        return null;
    }

    public function assinaturas()
    {
        return $this->hasMany(Assinatura::class);
    }

    public function assinaturaAtiva()
    {
        return $this->hasOne(Assinatura::class)
            ->where('status', 'authorized')
            ->latestOfMany();
    }

    /**
     * Retorna a assinatura vigente (authorized ou pending) mais recente.
     * Utilizada no SubscriptionController e na view de cancelamento.
     */
    public function assinaturaVigente()
    {
        return $this->hasOne(Assinatura::class)
            ->whereIn('status', ['authorized', 'pending'])
            ->latestOfMany();
    }

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

    /**
     * Verifica se a empresa está ativa e com assinatura válida.
     */
    public function isAtiva(): bool
    {
        if (!$this->ativo) {
            return false;
        }

        $assinatura = $this->assinaturaAtiva;

        return $assinatura ? $assinatura->isValida() : false;
    }
}
