<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;
use App\Models\Cliente;
use Illuminate\Http\Request;

class VeiculoController extends Controller
{
 public function index(Request $request)
{
    $query = Veiculo::with('cliente');

    // Busca global
    if ($request->filled('search')) {

        $search = $request->search;

        $query->where(function ($q) use ($search) {

            $q->where('placa', 'like', "%{$search}%")
              ->orWhere('marca', 'like', "%{$search}%")
              ->orWhere('modelo', 'like', "%{$search}%")

              ->orWhereHas('cliente', function ($cliente) use ($search) {

                    $cliente->where(
                        'nome',
                        'like',
                        "%{$search}%"
                    );

              });

        });
    }

    // Filtro individual de placa
    if ($request->filled('placa')) {

        $query->where(
            'placa',
            'like',
            "%{$request->placa}%"
        );
    }

    // Filtro individual de marca
    if ($request->filled('marca')) {

        $query->where(
            'marca',
            'like',
            "%{$request->marca}%"
        );
    }

    // Filtro individual de modelo
    if ($request->filled('modelo')) {

        $query->where(
            'modelo',
            'like',
            "%{$request->modelo}%"
        );
    }

    // Cliente
    if ($request->filled('cliente_id')) {

        $query->where(
            'cliente_id',
            $request->cliente_id
        );
    }

    $veiculos = $query
        ->latest()
        ->paginate(10)
        ->withQueryString();

    $clientes = Cliente::orderBy('nome')->get();

    return view(
        'veiculos.index',
        compact(
            'veiculos',
            'clientes'
        )
    );
}
public function create(Request $request)
{
    abort_if(!auth()->user()->isAdmin(), 403);

    $clientes = Cliente::orderBy('nome')->get();

    $clienteId = $request->cliente;

    return view('veiculos.create', compact(
        'clientes',
        'clienteId'
    ));
}
public function store(Request $request)
{
    abort_if(!auth()->user()->isAdmin(), 403);

    $request->validate([
        'cliente_id' => 'required|exists:clientes,id',
        'marca' => 'required',
        'modelo' => 'required',
        'placa' => 'required|unique:veiculos,placa',
    ]);

    Veiculo::create($request->all());

    return redirect()
        ->route('veiculos.index')
        ->with('success', 'Veículo cadastrado com sucesso!');
}

public function edit(Veiculo $veiculo)
{
    abort_if(!auth()->user()->isAdmin(), 403);

    $clientes = Cliente::orderBy('nome')->get();

    return view('veiculos.edit', compact('veiculo', 'clientes'));
}

public function update(Request $request, Veiculo $veiculo)
{
    abort_if(!auth()->user()->isAdmin(), 403);

    $request->validate([
        'cliente_id' => 'required|exists:clientes,id',
        'marca' => 'required',
        'modelo' => 'required',
        'placa' => 'required|unique:veiculos,placa,' . $veiculo->id,
    ]);

    $veiculo->update($request->all());

    return redirect()
        ->route('veiculos.index')
        ->with('success', 'Veículo atualizado com sucesso!');
}

public function destroy(Veiculo $veiculo)
{
    abort_if(!auth()->user()->isAdmin(), 403);

    $veiculo->delete();

    return redirect()
        ->route('veiculos.index')
        ->with('success', 'Veículo excluído com sucesso!');
}
public function show(Veiculo $veiculo)
{
    $veiculo->load([
        'cliente',
        'ordensServico'
    ]);

    return view(
        'veiculos.show',
        compact('veiculo')
    );
}
}