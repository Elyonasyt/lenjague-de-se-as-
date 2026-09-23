<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador LSM</title>
    <style>
        *{box-sizing:border-box;font-family:Arial,sans-serif}
        body{margin:0;background:#eef2f7;color:#111}
        .sidebar{position:fixed;left:0;top:0;width:245px;height:100vh;background:linear-gradient(#0d47ff,#00c6ff);padding:28px 18px;color:#fff;overflow:auto}
        .sidebar h2{text-align:center}
        .sidebar button,.sidebar a{display:block;width:100%;padding:13px;margin:8px 0;border:0;border-radius:10px;background:rgba(255,255,255,.12);color:#fff;text-align:left;text-decoration:none;cursor:pointer;font-weight:bold}
        .main{margin-left:245px;padding:30px}
        .section{display:none;background:#fff;border-radius:18px;padding:24px;box-shadow:0 5px 18px rgba(0,0,0,.08);overflow:auto}
        .section.active{display:block}
        .form-grid{display:flex;gap:10px;flex-wrap:wrap;align-items:center}
        input,select,textarea{padding:10px;border:1px solid #ccc;border-radius:8px}
        button{padding:9px 14px;border:0;border-radius:8px;cursor:pointer}
        .green{background:#16a34a;color:#fff}.yellow{background:#fbbf24}.red{background:#dc2626;color:#fff}.blue{background:#0284c7;color:#fff}
        table{width:100%;border-collapse:collapse;margin-top:18px;min-width:850px}
        th{background:#0d47ff;color:#fff}
        th,td{padding:10px;border-bottom:1px solid #ddd;text-align:center}
        img.thumb{width:70px;height:70px;object-fit:contain;background:#f5f5f5;border-radius:8px}
        .alert{padding:12px;border-radius:10px;margin:12px 0}.ok{background:#dcfce7;color:#166534}.bad{background:#fee2e2;color:#991b1b}
        @media(max-width:900px){.sidebar{position:static;width:100%;height:auto}.main{margin-left:0}.sidebar button,.sidebar a{display:inline-block;width:auto}}
    </style>
</head>
<body>

<div class="sidebar">
    <h2>LSM 🤟</h2>

    <button onclick="showSection('usuarios')">👤 Usuarios</button>
    <button onclick="showSection('categorias')">📂 Categorías</button>
    <button onclick="showSection('palabras')">📝 Palabras</button>
    <button onclick="showSection('imagenes')">🖼️ Imágenes</button>
    <button onclick="showSection('muestras')">🧠 Muestras IA</button>
    <button onclick="showSection('traducciones')">🔄 Traducciones</button>

    <a href="{{ route('bitacora') }}">📊 Bitácora</a>
    <a href="{{ route('logs') }}">📄 Logs Laravel</a>
    <a href="{{ route('traductor') }}">🤟 Traductor</a>
    <a href="{{ route('logout') }}">⏻ Cerrar sesión</a>
</div>

<div class="main">
    <h1>Panel de administración</h1>

    @if(session('success'))
        <div class="alert ok">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert bad">{{ $errors->first() }}</div>
    @endif

    <section id="usuarios" class="section active">
        <h2>Usuarios</h2>

        <form action="{{ route('usuarios.store') }}" method="POST" class="form-grid">
            @csrf
            <input name="first_name" placeholder="Nombre" required>
            <input name="last_name" placeholder="Apellido paterno" required>
            <input name="middle_name" placeholder="Apellido materno">
            <input type="email" name="email" placeholder="Correo" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <select name="role">
                <option value="USER">USER</option>
                <option value="ADMIN">ADMIN</option>
            </select>
            <button class="green">Agregar</button>
        </form>

        <table>
            <tr>
                <th>ID</th><th>Nombre</th><th>Apellidos</th><th>Email</th>
                <th>Rol</th><th>Registro</th><th>Acciones</th>
            </tr>

            @foreach($usuarios as $u)
                <tr>
                    <form action="{{ route('usuarios.update', $u->id_user) }}" method="POST">
                        @csrf
                        <td>{{ $u->id_user }}</td>
                        <td><input name="first_name" value="{{ $u->first_name }}" required></td>
                        <td>
                            <input name="last_name" value="{{ $u->last_name }}" required>
                            <input name="middle_name" value="{{ $u->middle_name }}">
                        </td>
                        <td><input type="email" name="email" value="{{ $u->email }}" required></td>
                        <td>
                            <select name="role">
                                <option value="USER" {{ $u->role==='USER' ? 'selected' : '' }}>USER</option>
                                <option value="ADMIN" {{ $u->role==='ADMIN' ? 'selected' : '' }}>ADMIN</option>
                            </select>
                        </td>
                        <td>{{ $u->registration_date }}</td>
                        <td>
                            <input type="password" name="password" placeholder="Nueva contraseña">
                            <button class="yellow">Guardar</button>
                            <a href="{{ route('usuarios.delete', $u->id_user) }}" onclick="return confirm('¿Eliminar usuario?')">
                                <button type="button" class="red">Eliminar</button>
                            </a>
                        </td>
                    </form>
                </tr>
            @endforeach
        </table>
    </section>

    <section id="categorias" class="section">
        <h2>Categorías</h2>

        <form action="{{ route('categorias.store') }}" method="POST" class="form-grid">
            @csrf
            <input name="nombre_categoria" placeholder="Nombre categoría" required>
            <select name="tipo_categoria" required>
                <option value="NUMEROS">NUMEROS</option>
                <option value="LETRAS">LETRAS</option>
                <option value="PALABRAS">PALABRAS</option>
                <option value="COLORES">COLORES</option>
            </select>
            <input name="descripcion" placeholder="Descripción">
            <button class="green">Agregar</button>
        </form>

        <table>
            <tr><th>ID</th><th>Nombre</th><th>Tipo</th><th>Descripción</th><th>Acciones</th></tr>

            @foreach($categorias as $c)
                <tr>
                    <form action="{{ route('categorias.update', $c->id_categoria) }}" method="POST">
                        @csrf
                        <td>{{ $c->id_categoria }}</td>
                        <td><input name="nombre_categoria" value="{{ $c->nombre_categoria }}" required></td>
                        <td>
                            <select name="tipo_categoria">
                                @foreach(['NUMEROS','LETRAS','PALABRAS','COLORES'] as $tipo)
                                    <option value="{{ $tipo }}" {{ $c->tipo_categoria===$tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input name="descripcion" value="{{ $c->descripcion }}"></td>
                        <td>
                            <button class="yellow">Guardar</button>
                            <a href="{{ route('categorias.delete', $c->id_categoria) }}" onclick="return confirm('¿Eliminar categoría?')">
                                <button type="button" class="red">Eliminar</button>
                            </a>
                        </td>
                    </form>
                </tr>
            @endforeach
        </table>
    </section>

    <section id="palabras" class="section">
        <h2>Palabras</h2>

        <form action="{{ route('palabras.store') }}" method="POST" class="form-grid">
            @csrf
            <input name="palabra_espanol" placeholder="Palabra / letra / número" required>
            <select name="id_categoria" required>
                @foreach($categorias as $c)
                    <option value="{{ $c->id_categoria }}">
                        {{ $c->tipo_categoria }} - {{ $c->nombre_categoria }}
                    </option>
                @endforeach
            </select>
            <button class="green">Agregar</button>
        </form>

        <table>
            <tr><th>ID</th><th>Palabra</th><th>Categoría</th><th>Acciones</th></tr>

            @foreach($palabras as $p)
                <tr>
                    <form action="{{ route('palabras.update', $p->id_palabra) }}" method="POST">
                        @csrf
                        <td>{{ $p->id_palabra }}</td>
                        <td><input name="palabra_espanol" value="{{ $p->palabra_espanol }}" required></td>
                        <td>
                            <select name="id_categoria">
                                @foreach($categorias as $c)
                                    <option value="{{ $c->id_categoria }}" {{ $p->id_categoria==$c->id_categoria ? 'selected' : '' }}>
                                        {{ $c->tipo_categoria }} - {{ $c->nombre_categoria }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <button class="yellow">Guardar</button>
                            <a href="{{ route('palabras.delete', $p->id_palabra) }}" onclick="return confirm('¿Eliminar palabra?')">
                                <button type="button" class="red">Eliminar</button>
                            </a>
                        </td>
                    </form>
                </tr>
            @endforeach
        </table>
    </section>

    <section id="imagenes" class="section">
        <h2>Imágenes de señas</h2>

        <form action="{{ route('imagenes.store') }}" method="POST" enctype="multipart/form-data" class="form-grid">
            @csrf
            <select name="id_categoria" required>
                @foreach($categorias as $c)
                    <option value="{{ $c->id_categoria }}">{{ $c->nombre_categoria }}</option>
                @endforeach
            </select>

            <select name="id_palabra">
                <option value="">Sin palabra</option>
                @foreach($palabras as $p)
                    <option value="{{ $p->id_palabra }}">{{ $p->palabra_espanol }}</option>
                @endforeach
            </select>

            <input type="file" name="imagen" accept="image/*" required>
            <input name="descripcion" placeholder="Descripción">
            <button class="green">Agregar imagen</button>
        </form>

        <table>
            <tr><th>ID</th><th>Vista</th><th>Palabra</th><th>Categoría</th><th>Descripción</th><th>Acciones</th></tr>

            @foreach($imagenes as $i)
                <tr>
                    <form action="{{ route('imagenes.update', $i->id_imagen) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <td>{{ $i->id_imagen }}</td>
                        <td><img class="thumb" src="{{ asset($i->ruta_imagen) }}"></td>
                        <td>
                            <select name="id_palabra">
                                <option value="">Sin palabra</option>
                                @foreach($palabras as $p)
                                    <option value="{{ $p->id_palabra }}" {{ $i->id_palabra==$p->id_palabra ? 'selected' : '' }}>
                                        {{ $p->palabra_espanol }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select name="id_categoria">
                                @foreach($categorias as $c)
                                    <option value="{{ $c->id_categoria }}" {{ $i->id_categoria==$c->id_categoria ? 'selected' : '' }}>
                                        {{ $c->nombre_categoria }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input name="descripcion" value="{{ $i->descripcion }}">
                            <input type="file" name="imagen" accept="image/*">
                        </td>
                        <td>
                            <button class="yellow">Guardar</button>
                            <a href="{{ route('imagenes.delete', $i->id_imagen) }}" onclick="return confirm('¿Eliminar imagen?')">
                                <button type="button" class="red">Eliminar</button>
                            </a>
                        </td>
                    </form>
                </tr>
            @endforeach
        </table>
    </section>

    <section id="muestras" class="section">
        <h2>Muestras de entrenamiento IA</h2>
        <table>
            <tr><th>ID palabra</th><th>Palabra</th><th>Muestras</th></tr>
            @foreach($muestras as $m)
                <tr>
                    <td>{{ $m->id_palabra }}</td>
                    <td>{{ $m->palabra_espanol }}</td>
                    <td>{{ $m->total }}</td>
                </tr>
            @endforeach
        </table>
    </section>

    <section id="traducciones" class="section">
        <h2>Traducciones</h2>
        <table>
            <tr><th>ID</th><th>Texto</th><th>Usuario</th><th>Tipo</th><th>Fecha</th><th>Acciones</th></tr>

            @foreach($traducciones as $t)
                <tr>
                    <form action="{{ route('traducciones.update', $t->id_traduccion) }}" method="POST">
                        @csrf
                        <td>{{ $t->id_traduccion }}</td>
                        <td><input name="texto_ingresado" value="{{ $t->texto_ingresado }}"></td>
                        <td>{{ $t->first_name }} {{ $t->last_name }}</td>
                        <td>
                            <select name="tipo_entrada">
                                @foreach(['TEXTO','VOZ','CAMARA'] as $tipo)
                                    <option value="{{ $tipo }}" {{ $t->tipo_entrada===$tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>{{ $t->fecha_traduccion }}</td>
                        <td>
                            <button class="yellow">Guardar</button>
                            <a href="{{ route('traducciones.delete', $t->id_traduccion) }}" onclick="return confirm('¿Eliminar traducción?')">
                                <button type="button" class="red">Eliminar</button>
                            </a>
                        </td>
                    </form>
                </tr>
            @endforeach
        </table>
    </section>
</div>

<script>
function showSection(id){
    document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
    document.getElementById(id).classList.add('active');
}
</script>

</body>
</html>
