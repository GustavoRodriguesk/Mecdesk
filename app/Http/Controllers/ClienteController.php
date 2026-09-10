<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $cleanSearch = preg_replace('/\D/', '', (string) $search);

        $clientes = Cliente::query()
            ->when($search, function ($query) use ($search, $cleanSearch) {
                $query->where(function ($q) use ($search, $cleanSearch) {
                    $q->where('nome', 'like', "%{$search}%")
                      ->orWhere('cpf_cnpj', 'like', "%{$search}%");

                    if (!empty($cleanSearch)) {
                        $q->orWhere('cpf_cnpj', 'like', "%{$cleanSearch}%")
                          ->orWhere('telefone', 'like', "%{$cleanSearch}%");
                    }
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('clientes.index', compact('clientes', 'search'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(StoreClienteRequest $request)
    {
        Cliente::create($request->except('empresa_id'));

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente cadastrado com sucesso!');
    }

    public function edit(Cliente $cliente)
    {
        $cliente->load('veiculos');

        return view('clientes.edit', compact('cliente'));
    }

    public function show(Cliente $cliente)
    {
        return redirect()->route('clientes.edit', $cliente->id);
    }

    public function update(UpdateClienteRequest $request, Cliente $cliente)
    {
        $cliente->update($request->except('empresa_id'));

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente atualizado com sucesso!');
    }

    public function destroy(Cliente $cliente)
    {
        abort_if(! auth()->user()->canDelete(), 403);

        if ($cliente->ordensServico()->exists()) {
            return redirect()
                ->route('clientes.index')
                ->with('error', 'Não é possível excluir este cliente pois existem ordens de serviço vinculadas a ele.');
        }

        try {
            $cliente->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()
                ->route('clientes.index')
                ->with('error', 'Não foi possível excluir o cliente devido a registros vinculados.');
        }

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente excluído com sucesso!');
    }
}