<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;
use App\Models\Cliente;
use Illuminate\Http\Request;

class VeiculoController extends Controller
{
   public function index()
{
    $veiculos = Veiculo::with('cliente')
        ->latest()
        ->paginate(10);

    return view('veiculos.index', compact('veiculos'));
}

public function create(Request $request)
{
    $clientes = Cliente::orderBy('nome')->get();

    $clienteId = $request->cliente;

    return view('veiculos.create', compact(
        'clientes',
        'clienteId'
    ));
}
public function store(Request $request)
{
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
    $clientes = Cliente::orderBy('nome')->get();

    return view('veiculos.edit', compact('veiculo', 'clientes'));
}

public function update(Request $request, Veiculo $veiculo)
{
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