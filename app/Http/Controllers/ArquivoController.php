<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\OrdemServicoFoto;
use App\Models\Scopes\EmpresaScope;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

/**
 * Serve arquivos gravados no disco "public" (storage/app/public) através do Laravel,
 * sem depender do symlink public/storage — necessário em hospedagens compartilhadas
 * (ex.: Hostgator) onde o public_html fica separado da pasta do projeto.
 */
class ArquivoController extends Controller
{
    /**
     * Foto de vistoria da OS. Protegida por URL assinada (middleware "signed:relative"),
     * o que permite exibi-la tanto no painel quanto na página pública de aprovação,
     * sem expor as fotos para quem não recebeu o link.
     */
    public function foto(int $foto): Response
    {
        $registro = OrdemServicoFoto::withoutGlobalScope(EmpresaScope::class)->findOrFail($foto);

        return $this->servir($registro->caminho_foto);
    }

    /**
     * Logotipo da empresa (conteúdo público de identidade visual).
     */
    public function logo(Empresa $empresa): Response
    {
        abort_if(empty($empresa->logo), 404);

        $caminho = ltrim(str_replace('storage/', '', $empresa->logo), '/');

        return $this->servir($caminho);
    }

    private function servir(string $caminho): Response
    {
        $disk = Storage::disk('public');

        // Impede path traversal (../) e arquivos inexistentes
        abort_if(str_contains($caminho, '..') || ! $disk->exists($caminho), 404);

        return $disk->response($caminho, null, [
            'Cache-Control' => 'private, max-age=86400',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
