<h1>Editar Cliente</h1>

<form action="{{ route('clientes.update', $cliente->id) }}" method="POST">

    @csrf
    @method('PUT')

    <input type="text" name="nome" value="{{ $cliente->nome }}">

    <br><br>

    <input type="text" name="cpf_cnpj" value="{{ $cliente->cpf_cnpj }}">

    <br><br>

    <input type="text" name="telefone" value="{{ $cliente->telefone }}">

    <br><br>

    <input type="email" name="email" value="{{ $cliente->email }}">

    <br><br>

    <textarea name="endereco">{{ $cliente->endereco }}</textarea>

    <br><br>

    <button type="submit">
        Atualizar
    </button>

</form>