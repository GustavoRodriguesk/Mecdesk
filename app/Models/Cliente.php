<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Scopes\EmpresaScope;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'empresa_id',
        'nome',
        'cpf_cnpj',
        'telefone',
        'telefone_2',
        'telefone_3',
        'possui_whatsapp',
        'email',
        'instagram',
        'endereco',
        'cep',
        'rua',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
    ];

    protected $casts = [
        'possui_whatsapp' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(
            new EmpresaScope
        );

        static::deleting(function ($cliente) {
            if (method_exists($cliente, 'isForceDeleting') && $cliente->isForceDeleting()) {
                $cliente->veiculos()->forceDelete();
            } else {
                $cliente->veiculos()->delete();
            }
        });

        static::creating(function ($cliente) {

            if (
                auth()->check() &&
                empty($cliente->empresa_id)
            ) {
                $cliente->empresa_id =
                    auth()->user()->empresa_id;
            }

        });

        static::saving(function ($cliente) {
            if ($cliente->rua || $cliente->cidade || $cliente->bairro) {
                $cliente->endereco = $cliente->endereco_completo;
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Mutators & Accessors
    |--------------------------------------------------------------------------
    */

    public function setCpfCnpjAttribute($value): void
    {
        $this->attributes['cpf_cnpj'] = $value ? preg_replace('/\D/', '', $value) : null;
    }

    public function getCpfCnpjFormatadoAttribute(): string
    {
        $c = preg_replace('/\D/', '', $this->cpf_cnpj ?? '');
        if (strlen($c) === 11) {
            return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $c);
        } elseif (strlen($c) === 14) {
            return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $c);
        }
        return $this->cpf_cnpj ?? '';
    }

    public function setTelefoneAttribute($value): void
    {
        $this->attributes['telefone'] = $value ? preg_replace('/\D/', '', $value) : null;
    }

    public function getTelefoneFormatadoAttribute(): string
    {
        $t = preg_replace('/\D/', '', $this->telefone ?? '');
        if (strlen($t) === 11) {
            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $t);
        } elseif (strlen($t) === 10) {
            return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $t);
        }
        return $this->telefone ?? '';
    }

    public function setEmailAttribute($value): void
    {
        $this->attributes['email'] = $value ? strtolower(trim($value)) : null;
    }

    public function getWhatsappLinkAttribute(): ?string
    {
        $telefone = preg_replace('/\D/', '', $this->telefone ?? '');

        if (empty($telefone)) {
            return null;
        }

        if (strlen($telefone) <= 11) {
            $telefone = '55' . $telefone;
        }

        return 'https://wa.me/' . $telefone;
    }

    public function setTelefone2Attribute($value): void
    {
        $this->attributes['telefone_2'] = $value ? preg_replace('/\D/', '', $value) : null;
    }

    public function getTelefone2FormatadoAttribute(): string
    {
        return $this->formatarTelefone($this->telefone_2);
    }

    public function setTelefone3Attribute($value): void
    {
        $this->attributes['telefone_3'] = $value ? preg_replace('/\D/', '', $value) : null;
    }

    public function getTelefone3FormatadoAttribute(): string
    {
        return $this->formatarTelefone($this->telefone_3);
    }

    private function formatarTelefone(?string $telefone): string
    {
        $t = preg_replace('/\D/', '', $telefone ?? '');
        if (strlen($t) === 11) {
            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $t);
        } elseif (strlen($t) === 10) {
            return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $t);
        }
        return $telefone ?? '';
    }

    public function setCepAttribute($value): void
    {
        $this->attributes['cep'] = $value ? preg_replace('/\D/', '', $value) : null;
    }

    public function getCepFormatadoAttribute(): string
    {
        $c = preg_replace('/\D/', '', $this->cep ?? '');
        if (strlen($c) === 8) {
            return preg_replace('/(\d{5})(\d{3})/', '$1-$2', $c);
        }
        return $this->cep ?? '';
    }

    public function setEstadoAttribute($value): void
    {
        $this->attributes['estado'] = $value ? strtoupper(substr(trim($value), 0, 2)) : null;
    }

    public function setInstagramAttribute($value): void
    {
        $v = trim((string) $value);
        $this->attributes['instagram'] = $v !== '' ? $v : null;
    }

    public function getEnderecoCompletoAttribute(): string
    {
        $partes = array_filter([
            $this->rua ? ($this->rua . ($this->numero ? ', ' . $this->numero : '')) : null,
            $this->complemento,
            $this->bairro,
            ($this->cidade || $this->estado) ? trim("{$this->cidade} - {$this->estado}", ' -') : null,
            $this->cep_formatado ? 'CEP ' . $this->cep_formatado : null,
        ]);

        if (!empty($partes)) {
            return implode(' - ', $partes);
        }

        return $this->endereco ?? '';
    }

    /*
    |--------------------------------------------------------------------------
    | Relacionamentos
    |--------------------------------------------------------------------------
    */

    public function empresa()
    {
        return $this->belongsTo(
            Empresa::class
        );
    }

    public function veiculos()
    {
        return $this->hasMany(
            Veiculo::class
        );
    }

    public function ordensServico()
    {
        return $this->hasMany(
            OrdemServico::class
        );
    }
}