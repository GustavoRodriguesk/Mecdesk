<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Veiculo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        $clientes = Cliente::orderBy('nome')->get();

        $clienteId = $request->cliente;

        return view('veiculos.create', compact(
            'clientes',
            'clienteId'
        ));
    }

    public function store(Request $request)
    {
        if ($request->filled('placa')) {
            $request->merge([
                'placa' => strtoupper(trim(preg_replace('/[^A-Za-z0-9]/', '', $request->placa)))
            ]);
        }

        $request->validate([
            'cliente_id' => [
                'required',
                Rule::exists('clientes', 'id')
                    ->where('empresa_id', auth()->user()->empresa_id),
            ],
            'marca'  => 'required',
            'modelo' => 'required',
            'placa'  => [
                'required',
                new \App\Rules\PlacaVeiculoRule,
                Rule::unique('veiculos', 'placa')
                    ->where('empresa_id', auth()->user()->empresa_id),
            ],
        ]);

        $dados = $request->except('empresa_id');
        $dados['empresa_id'] = auth()->user()->empresa_id;

        Veiculo::create($dados);

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
        if ($request->filled('placa')) {
            $request->merge([
                'placa' => strtoupper(trim(preg_replace('/[^A-Za-z0-9]/', '', $request->placa)))
            ]);
        }

        $request->validate([
            'cliente_id' => [
                'required',
                Rule::exists('clientes', 'id')
                    ->where('empresa_id', auth()->user()->empresa_id),
            ],
            'marca'  => 'required',
            'modelo' => 'required',
            'placa'  => [
                'required',
                new \App\Rules\PlacaVeiculoRule,
                Rule::unique('veiculos', 'placa')
                    ->ignore($veiculo->id)
                    ->where('empresa_id', auth()->user()->empresa_id),
            ],
        ]);

        $dados = $request->except('empresa_id');
        $dados['empresa_id'] = auth()->user()->empresa_id;

        $veiculo->update($dados);

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
        return redirect()->route('veiculos.edit', $veiculo->id);
    }
}
