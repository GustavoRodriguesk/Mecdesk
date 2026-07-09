<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
{
    $search = $request->search;

    $clientes = Cliente::where('nome', 'like', "%{$search}%")
        ->paginate(10);

    return view('clientes.index', compact('clientes', 'search'));
}

    public function create()
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        return view('clientes.create');
    }

    public function store(Request $request)
{
    abort_if(!auth()->user()->isAdmin(), 403);

    $request->validate([
        'nome' => 'required',
        'telefone' => 'required',
    ]);

    Cliente::create($request->except('empresa_id'));

    return redirect()
        ->route('clientes.index')
        ->with('success', 'Cliente cadastrado com sucesso!');
}
    public function edit(Cliente $cliente)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        return view('clientes.edit', compact('cliente'));
    }

    public function show(Cliente $cliente)
{
    $cliente->load('veiculos');

    return view('clientes.show', compact('cliente'));
}
    public function update(Request $request, Cliente $cliente)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $request->validate([
            'nome' => 'required',
            'telefone' => 'required',
        ]);

        $cliente->update($request->except('empresa_id'));

        return redirect()
    ->route('clientes.index')
    ->with('success', 'Cliente atualizado com sucesso!');
    }

    public function destroy(Cliente $cliente)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $cliente->delete();

        return redirect()
    ->route('clientes.index')
    ->with('success', 'Cliente excluído com sucesso!');
    }
}