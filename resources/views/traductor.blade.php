<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traductor de Lenguaje de Señas Mexicanas</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>

        /* =========================
           GENERAL
        ========================= */

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Poppins',sans-serif;
        }

        body{
            min-height:100vh;
            background:linear-gradient(135deg,#c8fff4,#9bf6ff,#72efdd,#64dfdf,#56cfe1);
            color:#023047;
            overflow-x:hidden;
        }

        /* =========================
           NAVBAR
        ========================= */

        .navbar{
            width:100%;
            height:95px;
            background:linear-gradient(90deg,#48cae4,#64dfdf,#72efdd);
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:0 40px;
            box-shadow:0 4px 15px rgba(0,0,0,0.1);
        }

        /* LOGO */

        .logo{
            display:flex;
            align-items:center;
            gap:15px;
        }

        .logo-icon{
            font-size:40px;
        }

        .logo h1{
            font-size:28px;
            color:#000;
        }

        /* =========================
           PERFIL USUARIO
        ========================= */

        .user-container{
            display:flex;
            align-items:center;
            gap:15px;
        }

        .perfil-card{
            display:flex;
            align-items:center;
            gap:12px;
            background:#111;
            padding:10px 20px;
            border-radius:50px;
            box-shadow:0 6px 15px rgba(0,0,0,0.15);
        }

        .perfil-icono{
            width:50px;
            height:50px;
            border-radius:50%;
            background:linear-gradient(135deg,#64dfdf,#48cae4);
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:24px;
            color:#fff;
        }

        .perfil-info{
            display:flex;
            flex-direction:column;
        }

        .perfil-info strong{
            color:#fff;
            font-size:15px;
            font-weight:600;
        }

        .perfil-info small{
            color:#ccc;
            font-size:11px;
        }

        /* BOTON CERRAR SESION */

        .logout-btn{
            background:#ff4d4d;
            color:#fff;
            padding:12px 18px;
            border-radius:15px;
            text-decoration:none;
            font-weight:600;
            transition:0.3s;
            box-shadow:0 4px 10px rgba(0,0,0,0.15);
        }

        .logout-btn:hover{
            background:#d90429;
            transform:translateY(-2px);
        }

        /* =========================
           LAYOUT
        ========================= */

        .layout{
            display:flex;
            gap:35px;
            padding:40px;
            max-width:1600px;
            margin:0 auto;
        }

        /* =========================
           HISTORIAL
        ========================= */

        .historial{
            width:330px;
            background:rgba(255,255,255,0.45);
            border:2px solid rgba(255,255,255,0.5);
            backdrop-filter:blur(15px);
            border-radius:30px;
            padding:25px;
            max-height:730px;
            overflow-y:auto;
        }

        .historial h3{
            text-align:center;
            font-size:24px;
            margin-bottom:20px;
            color:#0077b6;
        }

        .mensaje{
            background:rgba(255,255,255,0.55);
            border-radius:18px;
            padding:12px;
            margin-bottom:12px;
            transition:0.2s;
        }

        .mensaje:hover{
            transform:scale(1.02);
        }

        .mensaje a{
            text-decoration:none;
            color:#0096c7;
            font-weight:bold;
            display:block;
        }

        /* =========================
           TRADUCTOR
        ========================= */

        .traductor{
            flex:1;
            background:rgba(255,255,255,0.42);
            border:2px solid rgba(255,255,255,0.5);
            backdrop-filter:blur(15px);
            border-radius:35px;
            padding:45px;
            text-align:center;
        }

        .traductor h2{
            font-size:32px;
            color:#0096c7;
            margin-bottom:25px;
        }

        textarea{
            width:100%;
            height:180px;
            border:2px solid #90e0ef;
            outline:none;
            resize:none;
            border-radius:24px;
            padding:20px;
            font-size:18px;
            background:rgba(255,255,255,0.8);
        }

        .btn-primary{
            margin-top:25px;
            padding:15px 60px;
            border:none;
            border-radius:20px;
            background:#000;
            color:#fff;
            font-size:18px;
            font-weight:600;
            cursor:pointer;
            transition:0.3s;
        }

        .btn-primary:hover{
            background:#333;
        }

        /* =========================
           RESULTADOS
        ========================= */

        .imagen-traduccion{
            margin-top:40px;
        }

        .imagen-traduccion h3{
            font-size:28px;
            color:#0077b6;
            margin-bottom:20px;
        }

        .contenedor-resultado{
            display:flex;
            flex-wrap:wrap;
            justify-content:center;
            gap:20px;
        }

        .resultado-card{
            background:rgba(255,255,255,0.7);
            padding:20px;
            border-radius:25px;
            width:180px;
            box-shadow:0 8px 20px rgba(0,0,0,0.05);
            text-align:center;
        }

        .resultado-card img{
            width:140px;
            height:140px;
            object-fit:contain;
            border-radius:15px;
            background:#fff;
            padding:10px;
        }

    </style>

</head>

<body>

    <!-- =========================
         NAVBAR
    ========================= -->

    <div class="navbar">

        <div class="logo">

            <div class="logo-icon">
                🤟
            </div>

            <h1>
                Traductor de Lenguaje de Señas
            </h1>

        </div>

        @if(isset($usuario))

        <div class="user-container">

            <!-- PERFIL -->

            <div class="perfil-card">

                <div class="perfil-icono">
                    👤
                </div>

                <div class="perfil-info">

                    <strong>
                        {{ $usuario->first_name }}
                        {{ $usuario->last_name }}
                    </strong>

                    <small>
                        {{ $usuario->email }}
                    </small>

                </div>

            </div>

            <!-- CERRAR SESION -->

            <a href="{{ route('logout') }}" class="logout-btn">
                ⏻ Cerrar sesión
            </a>

        </div>

        @endif

    </div>

    <!-- =========================
         CONTENIDO
    ========================= -->

    <div class="layout">

        <!-- HISTORIAL -->

        <div class="historial">

            <h3>📜 Historial</h3>

            @forelse($historial as $m)

                <div class="mensaje">

                    <a href="{{ route('traductor.reconsultar', $m->id_traduccion) }}">

                        <strong>
                            {{ $m->texto_ingresado }}
                        </strong>

                    </a>

                    <small style="display:block; margin-top:5px; color:#555;">
                        {{ $m->fecha_traduccion }}
                    </small>

                </div>

            @empty

                <p style="text-align:center;color:#666;">
                    No hay historial disponible
                </p>

            @endforelse

        </div>

        <!-- TRADUCTOR -->

        <div class="traductor">

            <h2>Traducir texto</h2>

            <form action="{{ route('traductor.traducir') }}" method="POST">

                @csrf

                <textarea
                    name="texto"
                    placeholder="Escribe algo..."
                    required>{{ $textoAnterior ?? '' }}</textarea>

                <br>

                <button type="submit" class="btn-primary">
                    Traducir
                </button>

            </form>

            <!-- RESULTADO -->

            @if(isset($resultado) && count($resultado) > 0)

            <div class="imagen-traduccion">

                <h3>✨ Resultado</h3>

                <div class="contenedor-resultado">

                    @foreach($resultado as $item)

                        <div class="resultado-card">

                            @php
                                $ruta = $item->ruta_imagen ?? ($item->imagen ?? null);
                            @endphp

                            @if($ruta && $ruta != 'images/signs/no-image.png')

                                <img src="{{ asset($ruta) }}" alt="seña">

                            @else

                                <div style="
                                    height:140px;
                                    display:flex;
                                    align-items:center;
                                    justify-content:center;
                                    background:#eee;
                                    border-radius:15px;
                                ">
                                    <span style="font-size:40px; color:#999;">?</span>
                                </div>

                            @endif

                            <p style="
                                margin-top:10px;
                                font-weight:bold;
                                color:#0077b6;
                            ">
                                {{ $item->texto ?? '?' }}
                            </p>

                        </div>

                    @endforeach

                </div>

            </div>

            @endif

        </div>

    </div>

</body>

</html>