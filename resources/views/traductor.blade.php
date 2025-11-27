<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traductor - Lenguaje de Señas</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family: 'Poppins', sans-serif; }
        body {
            height:100vh; display:flex; justify-content:center; align-items:center;
            background: radial-gradient(circle at top left, #00ff99, #009966 80%);
            position: relative; color: white; overflow: hidden;
        }
        .fondo { position:absolute; width:100%; height:100%; overflow:hidden; z-index:0; }
        .burbuja { position:absolute; bottom:-100px; background: rgba(255,255,255,0.2); border-radius:50%; animation: subir 10s infinite ease-in; }
        @keyframes subir { from { transform: translateY(0) scale(1); opacity:1;} to { transform: translateY(-110vh) scale(1.3); opacity:0; } }

        .contenedor {
            position: relative; z-index:2; width:850px; padding:30px; border-radius:25px;
            background: rgba(255,255,255,0.1); backdrop-filter: blur(25px);
            box-shadow: 0 0 40px rgba(0,200,100,0.5), inset 0 0 20px rgba(255,255,255,0.2);
            display: flex; gap:20px;
        }

        .icon { font-size:90px; margin-bottom:15px; filter: drop-shadow(0 0 10px #00ff99); animation: brillo 3s infinite alternate; }
        @keyframes brillo { from { filter: drop-shadow(0 0 5px #00ff99); } to { filter: drop-shadow(0 0 20px #00ff00); } }

        h2 { margin-bottom:20px; letter-spacing:1.5px; font-weight:600; text-shadow:0 0 10px rgba(0,255,150,0.8); }

        textarea { width:100%; height:120px; padding:12px; border-radius:15px; border:none; resize:none;
            font-size:16px; text-align:center; outline:none; background: rgba(255,255,255,0.95); transition:0.3s; }
        textarea:focus { transform: scale(1.03); box-shadow:0 0 15px #00ff99; }

        button {
            width:100%; padding:14px; margin-top:15px; border:none; border-radius:12px;
            background: linear-gradient(90deg, #00ff99, #009966); color:black; font-weight:bold; font-size:16px;
            cursor:pointer; transition: all 0.3s ease; box-shadow:0 0 20px rgba(0,255,150,0.6);
        }
        button:hover { background: linear-gradient(90deg,#00ff66,#00cc33); transform: scale(1.08); box-shadow:0 0 30px rgba(0,255,150,0.9); }

        .historial { flex:1; max-height:350px; overflow-y:auto; background: rgba(0,0,0,0.3); padding:10px; border-radius:12px; }
        .historial h3 { margin-bottom:10px; font-size:18px; color:#00ff99; text-align:center; }
        .historial ul { list-style:none; }
        .historial li { padding:5px 0; border-bottom:1px solid rgba(0,255,150,0.3); }

        #logout-btn { position: fixed; bottom:20px; left:20px; padding:5px 10px; font-size:11px;
            border:none; border-radius:5px; font-weight:bold; cursor:pointer; box-shadow:0 0 6px rgba(255,153,51,0.7);
            background:#ff4444; color:white; z-index:100; transition: all 0.3s ease; }
        #logout-btn:hover { background:#ff7777; transform: scale(1.1); box-shadow:0 0 10px rgba(255,100,100,0.9); }

        .imagen-traduccion { margin-top:20px; text-align:center; }
        .imagen-traduccion img { width:150px; height:150px; border-radius:15px; box-shadow:0 0 10px #00ff99; }

        #video-container { margin-top:20px; text-align:center; display:none; }
        video { border-radius:15px; box-shadow:0 0 15px #00ff99; width:400px; height:300px; }
        #resultado { margin-top:10px; font-size:1.2em; font-weight:bold; color:#fff; }
    </style>
</head>
<body>

<!-- BOTÓN CERRAR SESIÓN -->
<button id="logout-btn" onclick="location.href='{{ url('/') }}'">Cerrar Sesión</button>

<!-- FONDO CON BURBUJAS -->
<div class="fondo">
    <div class="burbuja" style="width:80px; height:80px; left:10%; animation-delay:0s;"></div>
    <div class="burbuja" style="width:50px; height:50px; left:25%; animation-delay:2s;"></div>
    <div class="burbuja" style="width:100px; height:100px; left:40%; animation-delay:4s;"></div>
    <div class="burbuja" style="width:60px; height:60px; left:60%; animation-delay:1s;"></div>
    <div class="burbuja" style="width:90px; height:90px; left:75%; animation-delay:3s;"></div>
</div>

<!-- CONTENEDOR PRINCIPAL -->
<div class="contenedor">

    <!-- HISTORIAL IZQUIERDA -->
    <div class="historial">
        <h3>Historial</h3>
        <ul id="listaHistorial">
            @if(isset($palabra))
                <li>{{ $palabra }}</li>
            @endif
        </ul>
    </div>

    <!-- INPUT Y BOTÓN DERECHA -->
    <div style="flex: 1; display:flex; flex-direction: column; align-items:center;">
        <div class="icon">👋</div>
        <h2>Traductor de Palabras a Lenguaje de Señas</h2>

        <form action="{{ route('traductor.traducir') }}" method="POST">
            @csrf
            <textarea name="palabra" placeholder="Escribe una palabra..."></textarea><br>
            <button type="submit">Traducir</button>
        </form>

        @isset($imagen)
            <h3>Traducción para: "{{ $palabra }}"</h3>
            <img src="{{ $imagen }}" alt="Seña de {{ $palabra }}">
        @endisset

        <!-- BOTÓN PARA CÁMARA -->
        <button id="btnCamara" onclick="toggleCamara()">📷 Activar Cámara</button>

        <!-- VIDEO -->
        <div id="video-container">
            <video id="video" autoplay playsinline></video>
            <div id="resultado">Esperando detección...</div>
        </div>

        @if(isset($imagen))
            <div class="imagen-traduccion">
                <h3>Traducción:</h3>
                <img src="{{ $imagen }}" alt="{{ $palabra }}">
            </div>
        @endif
    </div>
</div>

<!-- Librerías de IA -->
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/hands/hands.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/drawing_utils/drawing_utils.js"></script>

<script>
    let camaraActiva = false;
    let camera;
    let hands;

    function toggleCamara() {
        const contenedor = document.getElementById('video-container');
        const boton = document.getElementById('btnCamara');

        if (!camaraActiva) {
            contenedor.style.display = 'block';
            boton.textContent = "❌ Detener Cámara";
            iniciarCamara();
            camaraActiva = true;
        } else {
            contenedor.style.display = 'none';
            boton.textContent = "📷 Activar Cámara";
            detenerCamara();
            camaraActiva = false;
        }
    }

    async function iniciarCamara() {
        const video = document.getElementById('video');
        hands = new Hands({ locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/hands/${file}` });

        hands.setOptions({
            maxNumHands: 1,
            modelComplexity: 1,
            minDetectionConfidence: 0.7,
            minTrackingConfidence: 0.7
        });

        hands.onResults(onResults);

        camera = new Camera(video, {
            onFrame: async () => {
                await hands.send({ image: video });
            },
            width: 400,
            height: 300
        });

        camera.start();
    }

    function detenerCamara() {
        if (camera) camera.stop();
    }

    function onResults(results) {
        const resultado = document.getElementById('resultado');
        if (results.multiHandLandmarks && results.multiHandLandmarks.length > 0) {
            resultado.textContent = "✋ Mano detectada correctamente";
        } else {
            resultado.textContent = "Esperando detección...";
        }
    }
</script>
</body>
</html>
