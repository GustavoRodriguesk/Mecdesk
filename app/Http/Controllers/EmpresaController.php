<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function edit()
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $empresa = auth()->user()->empresa;
        // Carrega os funcionários (usuários) dessa empresa
        $funcionarios = $empresa->users()->get();

        return view('empresa.edit', compact('empresa', 'funcionarios'));
    }

    public function update(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $request->validate([
            'nome_fantasia' => 'required|string|max:255',
            'cnpj' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
        ]);

        $empresa = auth()->user()->empresa;
        $empresa->update(
            $request->only([
                'nome_fantasia',
                'cnpj',
                'email',
                'telefone'
            ])
        );

        return redirect()->back()->with('success', 'Dados da empresa atualizados com sucesso!');
    }
}
