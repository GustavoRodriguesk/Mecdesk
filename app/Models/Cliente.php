<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Scopes\EmpresaScope;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'nome',
        'cpf_cnpj',
        'telefone',
        'email',
        'endereco',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(
            new EmpresaScope
        );

        static::creating(function ($cliente) {

            if (
                auth()->check() &&
                empty($cliente->empresa_id)
            ) {
                $cliente->empresa_id =
                    auth()->user()->empresa_id;
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