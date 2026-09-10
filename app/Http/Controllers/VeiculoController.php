<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Veiculo;
use App\Http\Requests\StoreVeiculoRequest;
use App\Http\Requests\UpdateVeiculoRequest;
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

    public function store(StoreVeiculoRequest $request)
    {
        $dados = $request->except('empresa_id');
        $dados['empresa_id'] = auth()->user()->empresa_id;

        Veiculo::create($dados);

        return redirect()
            ->route('veiculos.index')
            ->with('success', 'Veculo cadastrado com sucesso!');
    }

    public function edit(Veiculo $veiculo)
    {
        $clientes = Cliente::orderBy('nome')->get();

        return view('veiculos.edit', compact('veiculo', 'clientes'));
    }

    public function update(UpdateVeiculoRequest $request, Veiculo $veiculo)
    {
        $dados = $request->except('empresa_id');
        $dados['empresa_id'] = auth()->user()->empresa_id;

        $veiculo->update($dados);

        return redirect()
            ->route('veiculos.index')
            ->with('success', 'Veculo atualizado com sucesso!');
    }

    public function destroy(Veiculo $veiculo)
    {
        abort_if(! auth()->user()->canDelete(), 403);

        if ($veiculo->ordensServico()->exists()) {
            return redirect()
                ->route('veiculos.index')
                ->with('error', 'Não é possível excluir este veículo pois existem ordens de serviço vinculadas a ele.');
        }

        try {
            $veiculo->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()
                ->route('veiculos.index')
                ->with('error', 'Não foi possível excluir o veículo devido a registros vinculados.');
        }

        return redirect()
            ->route('veiculos.index')
            ->with('success', 'Veículo excluído com sucesso!');
    }

    public function show(Veiculo $veiculo)
    {
        return redirect()->route('veiculos.edit', $veiculo->id);
    }
}
