<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use App\Models\Scopes\EmpresaScope;

class OrdemServico extends Model
{
    use HasFactory;

    protected $table = 'ordem_servicos';

    protected $fillable = [
        'empresa_id',
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
        'approval_token',
        'approval_status',
        'approval_requested_at',
        'approval_response_at',
        'approval_comment',
        'approval_ip',
        'approval_user_agent',
    ];

    protected function casts(): array
    {
        return [
            'approval_requested_at' => 'datetime',
            'approval_response_at'  => 'datetime',
            'data_entrada'          => 'datetime',
            'data_saida'            => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::addGlobalScope(
            new EmpresaScope
        );

        static::creating(function ($ordem) {

            if (
                auth()->check() &&
                empty($ordem->empresa_id)
            ) {
                $ordem->empresa_id =
                    auth()->user()->empresa_id;
            }

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relacionamentos
    |--------------------------------------------------------------------------
    */

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
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
        return $this->hasMany(
            OrdemServicoItem::class,
            'ordem_servico_id'
        );
    }

    public function historicos()
    {
        return $this->hasMany(
            OrdemServicoHistorico::class,
            'ordem_servico_id'
        )->latest();
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getStatusFormatadoAttribute(): string
    {
        return match ($this->status) {
            'aberta'                => 'Aberta',
            'em_andamento'          => 'Em andamento',
            'aguardando_aprovacao'  => 'Aguardando aprovação',
            'aprovada'              => 'Aprovada',
            'reprovada'             => 'Reprovada',
            'concluida'             => 'Concluída',
            'entregue'              => 'Entregue',
            'cancelada'             => 'Cancelada',
            default                 => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'aberta'                => 'bg-blue-100 text-blue-800',
            'em_andamento'          => 'bg-yellow-100 text-yellow-800',
            'aguardando_aprovacao'  => 'bg-orange-100 text-orange-800',
            'aprovada'              => 'bg-green-100 text-green-800',
            'reprovada'             => 'bg-red-100 text-red-800',
            'concluida'             => 'bg-green-100 text-green-800',
            'entregue'              => 'bg-purple-100 text-purple-800',
            'cancelada'             => 'bg-red-100 text-red-800',
            default                 => 'bg-gray-100 text-gray-800',
        };
    }

    public function getApprovalStatusFormatadoAttribute(): string
    {
        return match ($this->approval_status) {
            'pending'  => 'Aguardando',
            'approved' => 'Aprovada pelo cliente',
            'rejected' => 'Reprovada pelo cliente',
            default    => '—',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Verifica se a aprovação já foi respondida.
     */
    public function isApprovalResponded(): bool
    {
        return in_array($this->approval_status, ['approved', 'rejected'], true);
    }

    /**
     * Gera um token de aprovação seguro.
     */
    public function generateApprovalToken(): void
    {
        $this->update([
            'approval_token'        => Str::uuid()->toString(),
            'approval_status'       => 'pending',
            'approval_requested_at' => now(),
            'status'                => 'aguardando_aprovacao',
        ]);
    }

    /**
     * Mensagem formatada para WhatsApp.
     */
    public function getWhatsappMessageAttribute(): string
    {
        $url = route('aprovacao.show', $this->approval_token);

        $veiculo = $this->veiculo
            ? $this->veiculo->marca . ' ' . $this->veiculo->modelo
            : 'Veículo';

        $valor = 'R$ ' . number_format($this->valor_total, 2, ',', '.');

        $cliente = $this->cliente->nome ?? 'Cliente';
        $primeiroNome = explode(' ', trim($cliente))[0];

        return "Olá {$primeiroNome}!\n\n"
            . "Sua Ordem de Serviço está pronta para aprovação.\n\n"
            . "Veículo:\n{$veiculo}\n\n"
            . "Valor:\n{$valor}\n\n"
            . "Clique no link abaixo para visualizar:\n{$url}\n\n"
            . "Obrigado!";
    }

    /**
     * Link wa.me com a mensagem já codificada.
     */
    public function getWhatsappLinkAttribute(): string
    {
        $telefone = preg_replace('/\D/', '', $this->cliente->telefone ?? '');

        if ($telefone && strlen($telefone) <= 11) {
            $telefone = '55' . $telefone;
        }

        return 'https://wa.me/' . $telefone . '?text=' . urlencode($this->whatsapp_message);
    }
}