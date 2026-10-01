<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}">

    <title>
        Cámara IA | LSM
    </title>


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
                    #ffffff
                );

            color: #023047;
        }


        header {

            padding: 18px 30px;

            background:
                rgba(
                    255,
                    255,
                    255,
                    .45
                );

            display: flex;

            justify-content:
                space-between;

            align-items: center;

            gap: 15px;
        }


        header h1 {

            margin: 0;

        }


        header a {

            background: #000;

            color: #fff;

            text-decoration: none;

            padding: 12px 18px;

            border-radius: 14px;

            font-weight: bold;
        }


        .layout {

            width:
                min(
                    1450px,
                    96%
                );

            margin: 24px auto;

            display: grid;

            grid-template-columns:
                1.2fr .8fr;

            gap: 24px;
        }


        .panel {

            background:
                rgba(
                    255,
                    255,
                    255,
                    .65
                );

            padding: 24px;

            border-radius: 28px;

            box-shadow:
                0 10px 25px
                rgba(0,0,0,.08);
        }


        .camera-box {

            position: relative;

            width:
                min(
                    720px,
                    100%
                );

            margin: auto;

            aspect-ratio:
                4 / 3;

            background: #111;

            border-radius: 22px;

            overflow: hidden;
        }


        #video,
        #canvas {

            position: absolute;

            inset: 0;

            width: 100%;

            height: 100%;

            object-fit: cover;

            transform:
                scaleX(-1);
        }


        #canvas {

            pointer-events: none;

        }


        .ia-result {

            background: #fff;

            border-radius: 18px;

            padding: 18px;

            margin-top: 20px;

            text-align: center;
        }


        #prediccion {

            font-size: 36px;

            font-weight: bold;

            color: #0077b6;
        }


        #confianza {

            font-size: 20px;

            margin-top: 5px;
        }


        button,
        select {

            padding: 12px 16px;

            border-radius: 14px;

            border: 0;

            font-size: 15px;
        }


        button {

            cursor: pointer;

            font-weight: bold;
        }


        select {

            width: 100%;

            border:
                2px solid #90e0ef;

            background: #fff;
        }


        .green {

            background: #16a34a;

            color: #fff;

        }


        .red {

            background: #ef4444;

            color: #fff;

        }


        .blue {

            background: #0284c7;

            color: #fff;

        }


        .black {

            background: #000;

            color: #fff;

        }


        .row {

            display: flex;

            gap: 10px;

            flex-wrap: wrap;

            margin: 14px 0;
        }


        textarea {

            width: 100%;

            min-height: 110px;

            border:
                2px solid #90e0ef;

            border-radius: 16px;

            padding: 14px;

            font-size: 17px;

            background: white;

            resize: vertical;
        }


        .progress {

            height: 18px;

            background: #ddd;

            border-radius: 10px;

            overflow: hidden;
        }


        #barra {

            height: 100%;

            width: 0;

            background: #16a34a;

            transition:
                width .15s;
        }


        .note {

            background: #fff7d6;

            padding: 14px;

            border-radius: 14px;

            line-height: 1.5;
        }


        #estadoReconocimiento,
        #estadoCamara {

            margin-top: 12px;

            font-weight: bold;
        }


        @media(
            max-width: 900px
        ) {

            .layout {

                grid-template-columns:
                    1fr;

            }


            header {

                flex-direction:
                    column;

                text-align: center;

            }

        }

    </style>

</head>


<body>


<header>

    <h1>

        📷 Cámara + Inteligencia Artificial

    </h1>


    <a href="{{ url('/traductor') }}">

        ← Traductor

    </a>

</header>



<div class="layout">


    <!-- ======================================= -->
    <!-- RECONOCIMIENTO -->
    <!-- ======================================= -->

    <section class="panel">


        <h2>

            🤟 Reconocimiento de señas

        </h2>



        <div class="camera-box">


            <video
                id="video"
                autoplay
                muted
                playsinline>
            </video>


            <canvas
                id="canvas">
            </canvas>


        </div>



        <div id="estadoCamara">

            Cámara apagada.

        </div>



        <div class="ia-result">


            <div id="prediccion">

                Esperando mano...

            </div>


            <div id="confianza">

                Confianza: 0%

            </div>


        </div>



        <div class="row">


            <button
                type="button"
                class="green"
                onclick="activarReconocimiento()">

                ▶ Iniciar reconocimiento

            </button>


            <button
                type="button"
                class="red"
                onclick="detenerReconocimiento()">

                ■ Detener

            </button>


        </div>



        <h3>

            Frase detectada

        </h3>


        <textarea
            id="frase"
            readonly
            placeholder="Las señas detectadas aparecerán aquí..."></textarea>



        <div class="row">


            <button
                type="button"
                class="black"
                onclick="guardarFrase()">

                💾 Guardar en historial

            </button>


            <button
                type="button"
                class="red"
                onclick="limpiarFrase()">

                🗑 Limpiar

            </button>


        </div>


        <div id="estadoReconocimiento"></div>


    </section>



    <!-- ======================================= -->
    <!-- ENTRENAMIENTO -->
    <!-- ======================================= -->

    <aside class="panel">


        <h2>

            🧠 Entrenamiento

        </h2>



        <div class="note">

            Selecciona una palabra o letra.

            <br><br>

            Después presiona:

            <br><br>

            <strong>
                📸 Capturar 100 muestras
            </strong>

            <br><br>

            Mantén la seña frente a la cámara,
            pero cambia ligeramente la posición,
            inclinación y distancia de tu mano.

        </div>



        <h3>

            Palabra / letra

        </h3>



        <select id="idPalabra">


            <option value="">

                Seleccione...

            </option>


            @foreach($palabras as $p)


                <option
                    value="{{ $p->id_palabra }}">


                    {{ $p->palabra_espanol }}


                </option>


            @endforeach


        </select>



        <h3>

            Captura

        </h3>



        <button
            id="btnCapturar"
            type="button"
            class="blue"
            onclick="capturarLote()">

            📸 Capturar 100 muestras

        </button>



        <div
            class="progress"
            style="margin-top:15px;">


            <div id="barra"></div>


        </div>



        <p id="estadoCaptura">

            0 / 100

        </p>



        <div
            class="note"
            style="margin-top:20px;">


            Cuando tengas muestras
            de varias palabras:

            <br><br>


            <code>

                py entrenar.py

            </code>


            <br><br>

            Después:

            <br><br>


            <code>

                py api.py

            </code>


        </div>


    </aside>


</div>



<script>

window.LSM_CONFIG = {

    csrf:
        document
            .querySelector(
                'meta[name="csrf-token"]'
            )
            .content,

    guardarMuestrasUrl:
        "{{ route('camara.muestras') }}",

    guardarTraduccionUrl:
        "{{ route('camara.guardarTraduccion') }}",

    iaUrl:
        "http://127.0.0.1:5001"

};

</script>



<script
src="https://cdn.jsdelivr.net/npm/@mediapipe/hands/hands.js">
</script>


<script
src="https://cdn.jsdelivr.net/npm/@mediapipe/drawing_utils/drawing_utils.js">
</script>


<script
src="{{ asset('js/camara.js') }}">
</script>


</body>

</html>