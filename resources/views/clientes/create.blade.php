<h1>Novo Cliente</h1>

<form action="{{ route('clientes.store') }}" method="POST">

    @csrf

    <input type="text" name="nome" placeholder="Nome">

    <br><br>

    <input type="text" name="cpf_cnpj" placeholder="CPF/CNPJ">

    <br><br>

    <input type="text" name="telefone" placeholder="Telefone">

    <br><br>

    <input type="email" name="email" placeholder="E-mail">

    <br><br>

    <textarea name="endereco" placeholder="Endereço"></textarea>

    <br><br>

    <button type="submit">
        Salvar
    </button>

</form>