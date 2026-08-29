<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmpresaController extends Controller
{
    public function edit()
    {
        abort_if(! auth()->user()->isAdmin(), 403);

        $empresa = auth()->user()->empresa;
        // Carrega os funcionários (usuários) dessa empresa
        $funcionarios = $empresa->users()->get();

        // Contadores para o dashboard da empresa
        $totalClientes = $empresa->clientes()->count();
        $totalOrdens = $empresa->ordens()->count();
        $totalImportacoesIA = 0; // Se houver modelo de importação futuramente

        return view('empresa.edit', compact(
            'empresa',
            'funcionarios',
            'totalClientes',
            'totalOrdens',
            'totalImportacoesIA'
        ));
    }

    public function update(Request $request)
    {
        abort_if(! auth()->user()->isAdmin(), 403);

        $request->validate([
            'nome_fantasia' => 'required|string|max:150',
            'razao_social'  => 'nullable|string|max:150',
            'cnpj'          => 'nullable|string|max:18',
            'email'         => 'nullable|email|max:100',
            'telefone'      => 'nullable|string|max:15',
            'whatsapp'      => 'nullable|string|max:15',
            'cep'           => 'nullable|string|max:9',
            'logradouro'    => 'nullable|string|max:100',
            'numero'        => 'nullable|string|max:8',
            'bairro'        => 'nullable|string|max:100',
            'cidade'        => 'nullable|string|max:50',
            'estado'        => 'nullable|string|max:2',
            'logo'          => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:5120',
        ], [
            'logo.image' => 'O arquivo selecionado deve ser uma imagem válida.',
            'logo.mimes' => 'O logotipo deve estar nos formatos: PNG, JPG, JPEG, WEBP, GIF ou SVG.',
            'logo.max'   => 'O logotipo não pode ser maior que 5 MB.',
        ]);

        $empresa = auth()->user()->empresa;
        if (! $empresa) {
            return redirect()->back()->withErrors(['logo' => 'Empresa não encontrada para o usuário atual.']);
        }

        $data = $request->except(['_token', '_method', 'logo', 'remover_logo', 'plano', 'ativo']);

        // Trata remoção explícita da logo se o usuário marcou para remover
        if ($request->boolean('remover_logo')) {
            if ($empresa->logo && Storage::disk('public')->exists($empresa->logo)) {
                Storage::disk('public')->delete($empresa->logo);
            }
            $data['logo'] = null;
        }

        // Trata upload de novo logotipo
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            // Apaga a logo anterior do disco público se existir
            if ($empresa->logo && Storage::disk('public')->exists($empresa->logo)) {
                Storage::disk('public')->delete($empresa->logo);
            }

            // Armazena no disk public
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $empresa->update($data);

        return redirect()->back()->with('success', 'Dados da empresa atualizados com sucesso!');
    }
}
