<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Http\Requests\UpdateEmpresaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmpresaController extends Controller
{
    public function edit()
    {
        abort_if(! auth()->user()->canManageCompany(), 403);

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

    public function update(UpdateEmpresaRequest $request)
    {
        $empresa = auth()->user()->empresa;
        if (! $empresa) {
            return redirect()->back()->withErrors(['logo' => 'Empresa não encontrada para o usuário atual.']);
        }

        $data = $request->except(['_token', '_method', 'logo', 'remover_logo', 'plano', 'ativo']);

        if ($request->has('controle_estoque')) {
            $data['controle_estoque'] = $request->boolean('controle_estoque');
        }

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
