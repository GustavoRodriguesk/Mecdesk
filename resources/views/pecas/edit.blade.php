<form action="{{ route('pecas.update', $peca->id) }}" method="POST">

    @csrf
    @method('PUT')

    <input type="text"
           name="nome"
           value="{{ $peca->nome }}">

    <input type="text"
           name="codigo"
           value="{{ $peca->codigo }}">

    <input type="number"
           step="0.01"
           name="valor_unitario"
           value="{{ $peca->valor_unitario }}">

    <input type="number"
           name="estoque"
           value="{{ $peca->estoque }}">

</form>