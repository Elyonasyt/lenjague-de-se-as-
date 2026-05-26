<!DOCTYPE html>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Admin</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial;
        }

        body{
            display:flex;
            background:#eef2f7;
            font-size:18px; /* Aumenta tamaño general */
        }

        /* SIDEBAR */

        .sidebar{
            width:260px;
            height:100vh;
            background:linear-gradient(#0d47ff,#00c6ff);
            color:white;
            padding:40px 20px;
            position:fixed;
        }

        .sidebar h2{
            text-align:center;
            margin-bottom:50px;
            font-size:28px;
        }

        .sidebar a{
            display:block;
            padding:16px;
            color:white;
            text-decoration:none;
            border-radius:10px;
            margin-bottom:12px;
            cursor:pointer;
            font-size:18px;
        }

        .sidebar a:hover{
            background:rgba(255,255,255,0.2);
        }

        /* MAIN */

        .main{
            margin-left:260px;
            padding:40px;
            width:100%;
        }

        .main h1{
            font-size:32px;
            margin-bottom:20px;
        }

        .seccion{
            display:none;
            background:white;
            padding:35px;
            border-radius:12px;
            box-shadow:0 5px 10px rgba(0,0,0,0.1);
        }

        /* FORMULARIOS */

        input, select{
            padding:10px;
            margin:8px;
            font-size:16px;
            border-radius:6px;
            border:1px solid #ccc;
        }

        /* TABLAS */

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:25px;
            font-size:16px;
        }

        thead{
            background:#0d47ff;
            color:white;
        }

        th,td{
            padding:14px;
            text-align:center;
        }

        tr{
            border-bottom:1px solid #ddd;
        }

        /* BOTONES */

        button{
            padding:8px 14px;
            border:none;
            border-radius:6px;
            cursor:pointer;
            font-size:15px;
        }

        .editar{background:#ffc107;}
        .eliminar{background:#dc3545;color:white;}
        .guardar{background:#28a745;color:white;}
        .cancelar{background:#6c757d;color:white;}
        .agregar{
            background:#28a745;
            color:white;
            margin-bottom:15px;
            padding:10px 18px;
        }

    </style>

    <script>

        /* MOSTRAR SECCIONES */

        function mostrar(seccion){

            document.querySelectorAll(".seccion").forEach(s=>{
                s.style.display="none"
            })

            document.getElementById(seccion).style.display="block"

        }

        window.onload=function(){
            mostrar('usuarios')
        }

        /* EDITAR FILA GENERICA */

        function editarFila(id){

            document.getElementById("texto_"+id).style.display="none"
            document.getElementById("input_"+id).style.display="inline"

            if(document.getElementById("texto_tipo_"+id)){
                document.getElementById("texto_tipo_"+id).style.display="none"
                document.getElementById("input_tipo_"+id).style.display="inline"
            }

            document.getElementById("btnEditar_"+id).style.display="none"
            document.getElementById("btnGuardar_"+id).style.display="inline"
            document.getElementById("btnCancelar_"+id).style.display="inline"

        }

        function cancelarFila(id){

            document.getElementById("texto_"+id).style.display="inline"
            document.getElementById("input_"+id).style.display="none"

            if(document.getElementById("texto_tipo_"+id)){
                document.getElementById("texto_tipo_"+id).style.display="inline"
                document.getElementById("input_tipo_"+id).style.display="none"
            }

            document.getElementById("btnEditar_"+id).style.display="inline"
            document.getElementById("btnGuardar_"+id).style.display="none"
            document.getElementById("btnCancelar_"+id).style.display="none"

        }

        /* PALABRAS */

        function editarPalabra(id){

            document.getElementById("texto_palabra_"+id).style.display="none"
            document.getElementById("input_palabra_"+id).style.display="inline"

            document.getElementById("texto_cat_"+id).style.display="none"
            document.getElementById("input_cat_"+id).style.display="inline"

            document.getElementById("guardar_palabra_"+id).style.display="inline"
            document.getElementById("cancelar_palabra_"+id).style.display="inline"

        }

        function cancelarPalabra(id){

            document.getElementById("texto_palabra_"+id).style.display="inline"
            document.getElementById("input_palabra_"+id).style.display="none"

            document.getElementById("texto_cat_"+id).style.display="inline"
            document.getElementById("input_cat_"+id).style.display="none"

            document.getElementById("guardar_palabra_"+id).style.display="none"
            document.getElementById("cancelar_palabra_"+id).style.display="none"

        }

        /* TRADUCCIONES */

        function editarTraduccion(id){

            document.getElementById("texto_trad_"+id).style.display="none"
            document.getElementById("input_trad_"+id).style.display="inline"

            document.getElementById("guardar_trad_"+id).style.display="inline"
            document.getElementById("cancelar_trad_"+id).style.display="inline"

        }

        function cancelarTraduccion(id){

            document.getElementById("texto_trad_"+id).style.display="inline"
            document.getElementById("input_trad_"+id).style.display="none"

            document.getElementById("guardar_trad_"+id).style.display="none"
            document.getElementById("cancelar_trad_"+id).style.display="none"

        }

    </script>

</head>

<body>

<div class="sidebar">

    <h2>LSM 🤟</h2>

    <a onclick="mostrar('usuarios')">Usuarios</a> <a onclick="mostrar('categorias')">Categorías</a> <a onclick="mostrar('palabras')">Palabras</a> <a onclick="mostrar('traducciones')">Traducciones</a>

</div>

<div class="main">

    <h1>Panel de Administración</h1>

    <!-- ===================== USUARIOS ===================== -->

    <div id="usuarios" class="seccion">

        <h2>Usuarios</h2>

        <form action="/usuarios/store" method="POST">
            @csrf

            <input type="text" name="first_name" placeholder="Nombre" required>
            <input type="text" name="last_name" placeholder="Apellido Paterno" required>
            <input type="text" name="middle_name" placeholder="Apellido Materno">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Contraseña" required>

            <button type="submit" class="agregar">Agregar Usuario</button>

        </form>

        <table>

            <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido P</th>
                <th>Apellido M</th>
                <th>Email</th>
                <th>Password</th>
                <th>Registro</th>
                <th>Acciones</th>
            </tr>
            </thead>

            <tbody>

            @foreach($usuarios as $u)

                <tr>

                    <form action="/usuarios/update/{{ $u->id_user }}" method="POST">
                        @csrf

                        <td>{{ $u->id_user }}</td>

                        <td>

<span id="texto_nombre_{{ $u->id_user }}">
{{ $u->first_name }}
</span>

                            <input
                                type="text"
                                name="first_name"
                                value="{{ $u->first_name }}"
                                id="input_nombre_{{ $u->id_user }}"
                                style="display:none">

                        </td>

                        <td>

<span id="texto_ap_{{ $u->id_user }}">
{{ $u->last_name }}
</span>

                            <input
                                type="text"
                                name="last_name"
                                value="{{ $u->last_name }}"
                                id="input_ap_{{ $u->id_user }}"
                                style="display:none">

                        </td>

                        <td>

<span id="texto_am_{{ $u->id_user }}">
{{ $u->middle_name }}
</span>

                            <input
                                type="text"
                                name="middle_name"
                                value="{{ $u->middle_name }}"
                                id="input_am_{{ $u->id_user }}"
                                style="display:none">

                        </td>

                        <td>

<span id="texto_email_{{ $u->id_user }}">
{{ $u->email }}
</span>

                            <input
                                type="email"
                                name="email"
                                value="{{ $u->email }}"
                                id="input_email_{{ $u->id_user }}"
                                style="display:none">

                        </td>

                        <td>

<span id="texto_pass_{{ $u->id_user }}">
{{ $u->password }}
</span>

                            <input
                                type="text"
                                name="password"
                                value="{{ $u->password }}"
                                id="input_pass_{{ $u->id_user }}"
                                style="display:none">

                        </td>

                        <td>{{ $u->registration_date }}</td>

                        <td>

                            <button
                                type="button"
                                class="editar"
                                onclick="editarUsuario('{{ $u->id_user }}')">
                                Editar </button>

                            <button
                                type="submit"
                                class="guardar"
                                id="guardar_user_{{ $u->id_user }}"
                                style="display:none">
                                Guardar </button>

                            <button
                                type="button"
                                class="cancelar"
                                onclick="cancelarUsuario('{{ $u->id_user }}')"
                                id="cancelar_user_{{ $u->id_user }}"
                                style="display:none">
                                Cancelar </button>

                            <a href="/usuarios/delete/{{ $u->id_user }}">
                                <button type="button" class="eliminar">
                                    Eliminar
                                </button>
                            </a>

                        </td>

                    </form>

                </tr>

            @endforeach

            </tbody>

        </table>

    </div>

    <!-- ===================== CATEGORIAS ===================== -->

    <div id="categorias" class="seccion">

        <h2>Categorías</h2>

        <form action="/categorias/store" method="POST">
            @csrf

            <input type="text" name="nombre_categoria" placeholder="Nombre" required>

            <select name="tipo_categoria" required>
                <option value="">Seleccione tipo</option>
                <option value="NUMEROS">NUMEROS</option>
                <option value="LETRAS">LETRAS</option>
                <option value="PALABRAS">PALABRAS</option>
                <option value="COLORES">COLORES</option>
            </select>

            <button type="submit" class="agregar">Agregar Categoría</button>

        </form>

        <table>

            <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Acciones</th>
            </tr>
            </thead>

            <tbody>

            @foreach($categorias as $c)

                <tr>

                    <form action="/categorias/update/{{ $c->id_categoria }}" method="POST">
                        @csrf

                        <td>{{ $c->id_categoria }}</td>

                        <td>

<span id="texto_{{ $c->id_categoria }}">
{{ $c->nombre_categoria }}
</span>

                            <input
                                type="text"
                                name="nombre_categoria"
                                value="{{ $c->nombre_categoria }}"
                                id="input_{{ $c->id_categoria }}"
                                style="display:none">

                        </td>

                        <td>

<span id="texto_tipo_{{ $c->id_categoria }}">
{{ $c->tipo_categoria }}
</span>

                            <select
                                name="tipo_categoria"
                                id="input_tipo_{{ $c->id_categoria }}"
                                style="display:none">

                                <option value="NUMEROS" {{ $c->tipo_categoria=='NUMEROS'?'selected':'' }}>NUMEROS</option>
                                <option value="LETRAS" {{ $c->tipo_categoria=='LETRAS'?'selected':'' }}>LETRAS</option>
                                <option value="PALABRAS" {{ $c->tipo_categoria=='PALABRAS'?'selected':'' }}>PALABRAS</option>
                                <option value="COLORES" {{ $c->tipo_categoria=='COLORES'?'selected':'' }}>COLORES</option>

                            </select>

                        </td>

                        <td>

                            <button
                                type="button"
                                class="editar"
                                id="btnEditar_{{ $c->id_categoria }}"
                                onclick="editarFila('{{ $c->id_categoria }}')">
                                Editar </button>

                            <button
                                type="submit"
                                class="guardar"
                                id="btnGuardar_{{ $c->id_categoria }}"
                                style="display:none">
                                Guardar </button>

                            <button
                                type="button"
                                class="cancelar"
                                id="btnCancelar_{{ $c->id_categoria }}"
                                onclick="cancelarFila('{{ $c->id_categoria }}')"
                                style="display:none">
                                Cancelar </button>

                            <a href="/categorias/delete/{{ $c->id_categoria }}">
                                <button type="button" class="eliminar">Eliminar</button>
                            </a>

                        </td>

                    </form>

                </tr>

            @endforeach

            </tbody>

        </table>

    </div>


    <!-- ===================== PALABRAS ===================== -->
    <!-- ===================== PALABRAS ===================== -->

    <div id="palabras" class="seccion">

        <h2>Palabras</h2>

        <form action="/palabras/store" method="POST">
            @csrf

            <input type="text" name="palabra_espanol" placeholder="Palabra">

            <!-- SELECT EN VEZ DE INPUT -->
            <select name="id_categoria" required>
                <option value="">Seleccione categoría</option>

                @foreach($categorias as $c)
                    <option value="{{ $c->id_categoria }}">
                        {{ $c->nombre_categoria }}
                    </option>
                @endforeach

            </select>

            <button class="agregar">Agregar Palabra</button>

        </form>

        <table>

            <thead>
            <tr>
                <th>ID</th>
                <th>Palabra</th>
                <th>Categoría</th>
                <th>Acciones</th>
            </tr>
            </thead>

            <tbody>

            @foreach($palabras as $p)

                <tr>

                    <form action="/palabras/update/{{ $p->id_palabra }}" method="POST">
                        @csrf

                        <td>{{ $p->id_palabra }}</td>

                        <!-- PALABRA -->
                        <td>
                        <span id="texto_palabra_{{ $p->id_palabra }}">
                            {{ $p->palabra_espanol }}
                        </span>

                            <input type="text"
                                   name="palabra_espanol"
                                   value="{{ $p->palabra_espanol }}"
                                   id="input_palabra_{{ $p->id_palabra }}"
                                   style="display:none">
                        </td>

                        <!-- 🔥 CATEGORIA (YA CON NOMBRE) -->
                        <td>

                        <span id="texto_cat_{{ $p->id_palabra }}">
                            {{ $p->nombre_categoria }}
                        </span>

                            <!-- SELECT PARA EDITAR -->
                            <select name="id_categoria"
                                    id="input_cat_{{ $p->id_palabra }}"
                                    style="display:none">

                                @foreach($categorias as $c)
                                    <option value="{{ $c->id_categoria }}"
                                        {{ $p->id_categoria == $c->id_categoria ? 'selected' : '' }}>
                                        {{ $c->nombre_categoria }}
                                    </option>
                                @endforeach

                            </select>

                        </td>

                        <td>

                            <button type="button"
                                    class="editar"
                                    onclick="editarPalabra('{{ $p->id_palabra }}')">
                                Editar
                            </button>

                            <button type="submit"
                                    class="guardar"
                                    id="guardar_palabra_{{ $p->id_palabra }}"
                                    style="display:none">
                                Guardar
                            </button>

                            <button type="button"
                                    class="cancelar"
                                    onclick="cancelarPalabra('{{ $p->id_palabra }}')"
                                    id="cancelar_palabra_{{ $p->id_palabra }}"
                                    style="display:none">
                                Cancelar
                            </button>

                            <a href="/palabras/delete/{{ $p->id_palabra }}">
                                <button type="button" class="eliminar">
                                    Eliminar
                                </button>
                            </a>

                        </td>

                    </form>

                </tr>

            @endforeach

            </tbody>

        </table>

    </div>

    <!-- ===================== TRADUCCIONES ===================== -->

    <div id="traducciones" class="seccion">

        <h2>Traducciones</h2>

        <table>

            <thead>
            <tr>
                <th>ID</th>
                <th>Texto</th>
                <th>Usuario</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
            </thead>

            <tbody>

            @foreach($traducciones as $t)

                <tr>

                    <form action="/traducciones/update/{{ $t->id_traduccion }}" method="POST">
                        @csrf

                        <td>{{ $t->id_traduccion }}</td>

                        <td>

                            <span id="texto_trad_{{ $t->id_traduccion }}">{{ $t->texto_ingresado }}</span>

                            <input type="text" name="texto_ingresado" value="{{ $t->texto_ingresado }}" id="input_trad_{{ $t->id_traduccion }}" style="display:none">

                        </td>

                        <td>{{ $t->first_name }} {{ $t->last_name }}</td>

                        <td>{{ $t->fecha_traduccion }}</td>

                        <td>

                            <button type="button" class="editar" onclick="editarTraduccion('{{ $t->id_traduccion }}')">Editar</button>

                            <button type="submit" class="guardar" id="guardar_trad_{{ $t->id_traduccion }}" style="display:none">Guardar</button>

                            <button type="button" class="cancelar" onclick="cancelarTraduccion('{{ $t->id_traduccion }}')" id="cancelar_trad_{{ $t->id_traduccion }}" style="display:none">Cancelar</button>

                            <a href="/traducciones/delete/{{ $t->id_traduccion }}">
                                <button type="button" class="eliminar">Eliminar</button>
                            </a>

                        </td>

                    </form>

                </tr>

            @endforeach

            </tbody>

        </table>

    </div>

</div>

</body>
</html>
