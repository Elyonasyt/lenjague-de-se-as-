<h2>Editar Palabra</h2>

<form action="{{ route('palabras.update',$palabra->id_palabra) }}" method="POST">

    @csrf

    <label>Palabra</label>

    <input type="text" name="palabra" value="{{ $palabra->palabra_espanol }}">

    <br><br>

    <label>ID Categoria</label>

    <input type="number" name="categoria" value="{{ $palabra->id_categoria }}">

    <br><br>

    <button type="submit">Actualizar</button>

</form>
