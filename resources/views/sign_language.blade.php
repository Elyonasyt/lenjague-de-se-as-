<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reconocimiento de Lenguaje de Señas 🖐️</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background: #f4f7fb;
            font-family: "Poppins", sans-serif;
            text-align: center;
            padding: 30px;
        }
        h1 {
            color: #2b7a78;
        }
        video {
            border: 3px solid #2b7a78;
            border-radius: 15px;
            margin-top: 10px;
        }
        canvas {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }
        #resultado {
            margin-top: 30px;
            font-size: 1.5em;
            font-weight: bold;
            color: #17252a;
        }
    </style>
</head>
<body>
<h1>🤖 Reconocimiento de Lenguaje de Señas en Tiempo Real</h1>

<video id="video" width="640" height="480" autoplay playsinline></video>
<canvas id="canvas" width="640" height="480"></canvas>

<div id="resultado">Esperando detección...</div>

<!-- Librerías -->
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/hands/hands.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/drawing_utils/drawing_utils.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@4.11.0"></script>

<!-- Tu script -->
<script src="{{ asset('js/sign.js') }}"></script>
</body>
</html>
