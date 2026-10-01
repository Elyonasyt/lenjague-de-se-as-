<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Traductor LSM</title>

    <style>

        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;

            background:
                linear-gradient(
                    135deg,
                    #ffffff,
                    #ffffff,
                    #ffffff,
                    #feffff,
                    #ffffff
                );

            color: #023047;
        }


        /* ============================= */
        /* NAVBAR */
        /* ============================= */

        .navbar {
            min-height: 90px;
            padding: 15px 35px;

            background: rgba(255,255,255,.35);

            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 20px;
        }

        .navbar h1 {
            margin: 0;
            color: #000;
        }


        .usuario {
            display: flex;
            align-items: center;
            gap: 12px;
        }


        .usuario-box {
            background: #111;
            color: #fff;

            padding: 12px 18px;

            border-radius: 25px;
        }


        .logout {
            background: #ef4444;
            color: #fff;

            padding: 12px 18px;

            border-radius: 15px;

            text-decoration: none;

            font-weight: bold;
        }



        /* ============================= */
        /* CONTENIDO */
        /* ============================= */

        .layout {
            width: min(1500px, 96%);

            margin: 25px auto;

            display: grid;

            grid-template-columns: 300px 1fr;

            gap: 25px;
        }


        .panel {
            background: rgba(255,255,255,.55);

            border-radius: 28px;

            padding: 24px;

            box-shadow:
                0 10px 25px rgba(0,0,0,.08);
        }



        /* ============================= */
        /* HISTORIAL */
        /* ============================= */

        .historial {
            max-height: 790px;
            overflow: auto;
        }


        .historial-item {
            background: rgba(255,255,255,.75);

            padding: 12px;

            border-radius: 14px;

            margin-bottom: 10px;
        }


        .historial-item a {
            color: #0077b6;

            text-decoration: none;

            font-weight: bold;
        }



        /* ============================= */
        /* MODOS */
        /* ============================= */

        .modos {
            display: flex;

            justify-content: center;

            gap: 12px;

            flex-wrap: wrap;

            margin-bottom: 25px;
        }


        .modo {
            padding: 13px 22px;

            border: none;

            border-radius: 16px;

            background: #fff;

            color: #0077b6;

            font-weight: bold;

            cursor: pointer;

            text-decoration: none;

            transition: all .3s ease;
        }


        .modo:hover {
            transform: translateY(-2px);
        }


        .modo.activo {
            background: #000;
            color: #fff;
        }



        /* ============================= */
        /* TEXTAREA */
        /* ============================= */

        textarea {
            width: 100%;

            height: 160px;

            padding: 18px;

            border: 2px solid #90e0ef;

            border-radius: 18px;

            font-size: 18px;

            resize: vertical;

            outline: none;
        }


        textarea:focus {
            border-color: #0077b6;

            box-shadow:
                0 0 0 3px rgba(0,119,182,.15);
        }



        /* ============================= */
        /* BOTONES */
        /* ============================= */

        .acciones {
            display: flex;

            justify-content: center;

            gap: 12px;

            flex-wrap: wrap;

            margin-top: 18px;
        }


        button {
            padding: 14px 22px;

            border: none;

            border-radius: 16px;

            font-weight: bold;

            cursor: pointer;

            font-size: 15px;
        }


        .btn-black {
            background: #000;
            color: #fff;
        }


        .btn-black:hover {
            background: #222;
        }


        .btn-mic {
            background: #ef4444;
            color: #fff;
        }


        .btn-mic:hover {
            background: #dc2626;
        }


        .btn-mic.escuchando {
            background: #16a34a;

            animation: pulso 1s infinite;
        }


        @keyframes pulso {

            0% {
                box-shadow:
                    0 0 0 0 rgba(22,163,74,.6);
            }

            70% {
                box-shadow:
                    0 0 0 12px rgba(22,163,74,0);
            }

            100% {
                box-shadow:
                    0 0 0 0 rgba(22,163,74,0);
            }
        }



        /* ============================= */
        /* ESTADO DEL MICRÓFONO */
        /* ============================= */

        #estadoVoz {
            text-align: center;

            font-weight: bold;

            margin-top: 15px;

            min-height: 25px;
        }


        .voz-info {
            display: none;

            margin-top: 15px;

            padding: 15px;

            background:
                rgba(255,255,255,.7);

            border-radius: 15px;

            text-align: center;
        }



        /* ============================= */
        /* RESULTADOS */
        /* ============================= */

        .resultado {
            display: flex;

            flex-wrap: wrap;

            justify-content: center;

            gap: 16px;

            margin-top: 28px;
        }


        .card {
            width: 160px;

            background: #fff;

            border-radius: 20px;

            padding: 14px;

            text-align: center;

            box-shadow:
                0 5px 15px rgba(0,0,0,.08);
        }


        .card img {
            width: 125px;

            height: 125px;

            object-fit: contain;

            border-radius: 12px;

            background: #f8f8f8;
        }


        .card strong {
            display: block;

            margin-top: 8px;

            color: #0077b6;
        }



        /* ============================= */
        /* RESPONSIVE */
        /* ============================= */

        @media(max-width: 850px) {

            .layout {
                grid-template-columns: 1fr;
            }


            .historial {
                max-height: 250px;
            }


            .navbar {
                flex-direction: column;

                text-align: center;
            }


            .usuario {
                flex-direction: column;
            }

        }

    </style>
</head>

<body>


<!-- ======================================== -->
<!-- NAVBAR -->
<!-- ======================================== -->

<div class="navbar">

    <h1>
        🤟 Sistema inteligente para traducir Lengua de Señas
    </h1>


    <div class="usuario">

        <div class="usuario-box">

            {{ $usuario->first_name }}
            {{ $usuario->last_name }}

            <br>

            <small>
                {{ $usuario->email }}
            </small>

        </div>


        <a class="logout"
           href="{{ route('logout') }}">

            Cerrar sesión

        </a>

    </div>

</div>



<!-- ======================================== -->
<!-- CONTENIDO PRINCIPAL -->
<!-- ======================================== -->

<div class="layout">


    <!-- ==================================== -->
    <!-- HISTORIAL -->
    <!-- ==================================== -->

    <aside class="panel historial">

        <h2>
            📜 Historial
        </h2>


        @forelse($historial as $m)

            <div class="historial-item">

                <a href="{{ route(
                    'traductor.reconsultar',
                    $m->id_traduccion
                ) }}">

                    {{ $m->texto_ingresado }}

                </a>

                <br>


                <small>

                    {{ $m->tipo_entrada }}

                    ·

                    {{ $m->fecha_traduccion }}

                </small>

            </div>

        @empty

            <p>
                No hay traducciones.
            </p>

        @endforelse

    </aside>



    <!-- ==================================== -->
    <!-- TRADUCTOR -->
    <!-- ==================================== -->

    <main class="panel">


        <!-- ================================ -->
        <!-- MODOS -->
        <!-- ================================ -->

        <div class="modos">

            <button
                class="modo activo"
                id="modoTexto"
                type="button"
                onclick="activarTexto()">

                ⌨️ Texto

            </button>


            <button
                class="modo"
                id="modoVoz"
                type="button"
                onclick="activarVoz()">

                🎤 Voz

            </button>


            <a
                class="modo"
                href="{{ route('camara') }}">

                📷 Cámara + IA

            </a>

        </div>



        <h2 id="tituloModo">

            Traducir texto a LSM

        </h2>



        <!-- ================================ -->
        <!-- FORMULARIO -->
        <!-- ================================ -->

        <form
            action="{{ route('traductor.traducir') }}"
            method="POST">

            @csrf


            <!-- Guarda si se utilizó texto o voz -->

            <input
                type="hidden"
                id="tipoEntrada"
                name="tipo_entrada"
                value="TEXTO">



            <!-- ============================ -->
            <!-- ÁREA DE TEXTO -->
            <!-- ============================ -->

            <textarea
                id="textoTraductor"
                name="texto"
                placeholder="Escribe una frase..."
                required>{{ $textoAnterior ?? '' }}</textarea>



            <!-- ============================ -->
            <!-- INFORMACIÓN DE VOZ -->
            <!-- ============================ -->

            <div
                id="informacionVoz"
                class="voz-info">

                🎤 Presiona el botón del micrófono y comienza a hablar.

                <br>

                Lo que digas aparecerá automáticamente arriba.

            </div>



            <!-- ============================ -->
            <!-- BOTONES -->
            <!-- ============================ -->

            <div class="acciones">


                <button
                    type="button"
                    id="btnMicrofono"
                    class="btn-mic"
                    onclick="iniciarVoz()"
                    style="display:none;">

                    🎤 Iniciar micrófono

                </button>



                <button
                    type="button"
                    id="btnLimpiar"
                    onclick="limpiarTexto()"
                    style="
                        background:#fff;
                        color:#000;
                    ">

                    🗑 Limpiar

                </button>



                <button
                    type="submit"
                    class="btn-black">

                    🤟 Traducir

                </button>

            </div>


            <div id="estadoVoz"></div>


        </form>



        <!-- ==================================== -->
        <!-- RESULTADO -->
        <!-- ==================================== -->

        @if(!empty($resultado))

            <h2
                style="
                    text-align:center;
                    margin-top:35px;
                ">

                ✨ Resultado

            </h2>


            <div class="resultado">


                @foreach($resultado as $item)


                    @php

                        $ruta =
                            is_array($item)
                            ? ($item['ruta_imagen'] ?? null)
                            : ($item->ruta_imagen ?? null);


                        $textoItem =
                            is_array($item)
                            ? ($item['texto'] ?? '?')
                            : ($item->texto ?? '?');

                    @endphp



                    <div class="card">


                        <img
                            src="{{ asset(
                                $ruta
                                ?: 'images/signs/no-image.svg'
                            ) }}"
                            alt="{{ $textoItem }}">


                        <strong>
                            {{ $textoItem }}
                        </strong>


                    </div>


                @endforeach


            </div>

        @endif


    </main>

</div>


<!-- ======================================== -->
<!-- JAVASCRIPT DE VOZ -->
<!-- ======================================== -->

<script src="{{ asset('js/voz.js') }}"></script>


</body>
</html>