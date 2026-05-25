<h1>Clientes</h1>

<a href="{{ route('clientes.create') }}">
    Novo Cliente
</a>

<hr>

@foreach($clientes as $cliente)

    <p>
        {{ $cliente->nome }}
        -
        {{ $cliente->telefone }}
    </p>

    <a href="{{ route('clientes.edit', $cliente->id) }}">
        Editar
    </a>

    <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST">
        @csrf
        @method('DELETE')

        <button type="submit">
            Excluir
        </button>
    </form>

    <hr>

@endforeach