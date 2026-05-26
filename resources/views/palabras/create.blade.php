<h2>Agregar Palabra</h2>

<form action="{{ route('palabras.store') }}" method="POST">

    @csrf

    <label>Palabra</label>

    <input type="text" name="palabra" required>

    <br><br>

    <label>ID Categoria</label>

    <input type="number" name="categoria">

    <br><br>

    <button type="submit">Guardar</button>

</form>
