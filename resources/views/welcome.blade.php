<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido - Lenguaje de Señas</title>
    <style>
        /* Reset */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }

        /* Fondo animado */
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: radial-gradient(circle at 20% 30%, #002bff, #000);
            overflow: hidden;
            color: white;
        }

        /* Partículas flotantes */
        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            animation: float 6s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); opacity: 0.7; }
            50% { transform: translateY(-50px) scale(1.5); opacity: 0.4; }
        }

        .particle:nth-child(1) { width: 6px; height: 6px; top: 20%; left: 15%; animation-delay: 0s; }
        .particle:nth-child(2) { width: 10px; height: 10px; top: 40%; left: 80%; animation-delay: 2s; }
        .particle:nth-child(3) { width: 8px; height: 8px; top: 70%; left: 25%; animation-delay: 4s; }
        .particle:nth-child(4) { width: 5px; height: 5px; top: 50%; left: 60%; animation-delay: 1s; }
        .particle:nth-child(5) { width: 12px; height: 12px; top: 80%; left: 10%; animation-delay: 3s; }

        /* Contenedor principal */
        .welcome-container {
            position: relative;
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(0, 217, 255, 0.8);
            backdrop-filter: blur(25px);
            box-shadow: 0 0 40px rgba(0, 200, 255, 0.4), 0 0 80px rgba(0, 100, 255, 0.2);
            border-radius: 30px;
            padding: 60px 50px;
            max-width: 480px;
            text-align: center;
            z-index: 2;
            overflow: hidden;
            animation: pulse 6s infinite;
        }

        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 40px #00ffff, 0 0 80px #0077ff; }
            50% { box-shadow: 0 0 60px #00bfff, 0 0 120px #0099ff; }
        }

        .icon {
            font-size: 90px;
            margin-bottom: 15px;
            text-shadow: 0 0 20px #00ffff, 0 0 40px #0077ff;
            animation: flotar 3s ease-in-out infinite;
        }

        @keyframes flotar {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        h1 {
            font-size: 40px;
            margin-bottom: 20px;
            background: linear-gradient(90deg, #00ffff, #ff00ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: texto 6s ease infinite;
        }

        @keyframes texto {
            0%, 100% { letter-spacing: 1px; }
            50% { letter-spacing: 3px; }
        }

        p {
            font-size: 17px;
            margin-bottom: 30px;
            color: #dffaff;
            line-height: 1.5;
        }

        /* Botones */
        .btn {
            display: inline-block;
            margin: 10px;
            padding: 14px 35px;
            border-radius: 10px;
            font-weight: bold;
            text-decoration: none;
            letter-spacing: 1px;
            font-size: 15px;
            color: white;
            border: 2px solid transparent;
            transition: all 0.4s ease;
            cursor: pointer;
        }

        .btn-login {
            background: linear-gradient(90deg, #00e5ff, #0072ff);
            box-shadow: 0 0 20px rgba(0, 170, 255, 0.7);
        }

        .btn-login:hover {
            transform: scale(1.1);
            background: linear-gradient(90deg, #0072ff, #00e5ff);
            box-shadow: 0 0 40px #00eaff;
        }

        .btn-register {
            background: linear-gradient(90deg, #ff00cc, #3333ff);
            box-shadow: 0 0 20px rgba(255, 0, 255, 0.7);
        }

        .btn-register:hover {
            transform: scale(1.1);
            background: linear-gradient(90deg, #3333ff, #ff00cc);
            box-shadow: 0 0 40px #ff00ff;
        }

        .btn-free {
            background: linear-gradient(90deg, #00ff66, #009933);
            box-shadow: 0 0 20px rgba(0, 255, 100, 0.7);
        }

        .btn-free:hover {
            transform: scale(1.1);
            background: linear-gradient(90deg, #009933, #00ff66);
            box-shadow: 0 0 40px rgba(0, 255, 100, 0.9);
        }

        /* Efecto clic */
        .btn:active {
            transform: scale(0.95);
            box-shadow: 0 0 10px #fff;
        }
    </style>
</head>
<body>

<!-- Partículas flotantes -->
<div class="particle"></div>
<div class="particle"></div>
<div class="particle"></div>
<div class="particle"></div>
<div class="particle"></div>

<!-- Contenedor principal -->
<div class="welcome-container">
    <div class="icon">🤟</div>
    <h1>Bienvenido a Lenguaje de Señas</h1>
    <p>Explora una experiencia futurista para aprender y traducir lenguaje de señas.</p>

    <!-- Botones funcionales -->
    <a href="/inicio" class="btn btn-login">🚀 Iniciar Sesión</a>
    <a href="/registro" class="btn btn-register">✨ Registrarse</a>

</div>

</body>
</html>
