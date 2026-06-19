<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmpresaController extends Controller
{
    public function edit()
    {
        abort_if(!auth()->user()->isAdmin(), 403);

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
        abort_if(!auth()->user()->isAdmin(), 403);

        $request->validate([
            'nome_fantasia' => 'required|string|max:255',
            'razao_social'  => 'nullable|string|max:255',
            'cnpj'          => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:255',
            'telefone'      => 'nullable|string|max:20',
            'whatsapp'      => 'nullable|string|max:20',
            'cep'           => 'nullable|string|max:9',
            'logradouro'    => 'nullable|string|max:255',
            'numero'        => 'nullable|string|max:50',
            'bairro'        => 'nullable|string|max:255',
            'cidade'        => 'nullable|string|max:255',
            'estado'        => 'nullable|string|max:2',
            'logo'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $empresa = auth()->user()->empresa;
        $data = $request->except(['_token', '_method', 'logo']);

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($empresa->logo) {
                Storage::disk('public')->delete($empresa->logo);
            }
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $empresa->update($data);

        return redirect()->back()->with('success', 'Dados da empresa atualizados com sucesso!');
    }
}
