<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\EmpresaScope;
use Illuminate\Support\Facades\URL;

class OrdemServicoFoto extends Model
{
    use HasFactory;

    protected $table = 'ordem_servico_fotos';

    protected $fillable = [
        'empresa_id',
        'ordem_servico_id',
        'caminho_foto',
        'descricao',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new EmpresaScope);

        static::creating(function ($foto) {
            if (auth()->check() && empty($foto->empresa_id)) {
                $foto->empresa_id = auth()->user()->empresa_id;
            }
        });
    }

    public function ordemServico()
    {
        return $this->belongsTo(OrdemServico::class, 'ordem_servico_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function getUrlAttribute(): string
    {
        if (str_starts_with($this->caminho_foto, 'http://') || str_starts_with($this->caminho_foto, 'https://')) {
            return $this->caminho_foto;
        }

        // URL assinada e relativa: não depende do symlink public/storage nem do APP_URL.
        // A expiração é arredondada para a hora cheia para a URL ficar estável (cache do navegador).
        return URL::temporarySignedRoute(
            'arquivos.foto',
            now()->startOfHour()->addHours(24),
            ['foto' => $this->id],
            false
        );
    }
}
