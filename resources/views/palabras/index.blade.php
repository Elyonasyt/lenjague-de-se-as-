<h2>Palabras</h2>

<a href="{{ route('palabras.create') }}">Agregar Palabra</a>

<table border="1">

    <tr>
        <th>ID</th>
        <th>Palabra</th>
        <th>Categoria</th>
        <th>Acciones</th>
    </tr>

    @foreach($palabras as $p)

        <tr>

            <td>{{ $p->id_palabra }}</td>

            <td>{{ $p->palabra_espanol }}</td>

            <td>{{ $p->id_categoria }}</td>

            <td>

                <a href="{{ route('palabras.edit',$p->id_palabra) }}">Editar</a>

                <a href="{{ route('palabras.delete',$p->id_palabra) }}">Eliminar</a>

            </td>

        </tr>

    @endforeach

</table>
