<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traductor - Lenguaje de Señas</title>
    <style>
        /* ===== RESET GENERAL ===== */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }

        /* ===== FONDO ANIMADO ===== */
        body {
            height: 100vh;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            background: radial-gradient(circle at top left, #00ff99, #009966 80%);
            position: relative;
            color: white;
        }

        .fondo {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .burbuja {
            position: absolute;
            bottom: -100px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            animation: subir 10s infinite ease-in;
        }

        @keyframes subir {
            from { transform: translateY(0) scale(1); opacity: 1; }
            to { transform: translateY(-110vh) scale(1.3); opacity: 0; }
        }

        /* ===== CONTENEDOR PRINCIPAL ===== */
        .contenedor {
            position: relative;
            z-index: 2;
            width: 500px;
            padding: 50px 40px;
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(25px);
            box-shadow: 0 0 40px rgba(0, 200, 100, 0.5), inset 0 0 20px rgba(255,255,255,0.2);
            text-align: center;
            animation: flotar 3s ease-in-out infinite;
        }

        @keyframes flotar { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }

        .icon {
            font-size: 90px;
            margin-bottom: 15px;
            filter: drop-shadow(0 0 10px #00ff99);
            animation: brillo 3s infinite alternate;
        }
        @keyframes brillo { from { filter: drop-shadow(0 0 5px #00ff99); } to { filter: drop-shadow(0 0 20px #00ff00); } }

        h2 {
            margin-bottom: 20px;
            letter-spacing: 1.5px;
            font-weight: 600;
            text-shadow: 0 0 10px rgba(0,255,150,0.8);
        }

        textarea {
            width: 90%;
            height: 120px;
            padding: 12px;
            border-radius: 15px;
            border: none;
            resize: none;
            font-size: 16px;
            text-align: center;
            outline: none;
            background: rgba(255,255,255,0.95);
            transition: 0.3s;
        }
        textarea:focus {
            transform: scale(1.03);
            box-shadow: 0 0 15px #00ff99;
        }

        button.traducir {
            display: inline-block;
            width: 92%;
            padding: 14px;
            margin-top: 15px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(90deg, #00ff99, #009966);
            color: black;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 0 20px rgba(0, 255, 150, 0.6);
        }
        button.traducir:hover {
            background: linear-gradient(90deg, #00ff66, #00cc33);
            transform: scale(1.08);
            box-shadow: 0 0 30px rgba(0, 255, 150, 0.9);
        }

        .luz {
            position: absolute;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(0,255,150,0.3), transparent);
            border-radius: 50%;
            animation: moverLuz 6s infinite alternate;
        }
        .luz:nth-child(1) { top: 15%; left: 10%; animation-delay: 0s; }
        .luz:nth-child(2) { bottom: 15%; right: 10%; animation-delay: 3s; }

        @keyframes moverLuz { 0% { transform: translate(0, 0); } 100% { transform: translate(50px, -50px); } }

    </style>
</head>
<body>

<!-- FONDO CON BURBUJAS -->
<div class="fondo">
    <div class="burbuja" style="width:80px; height:80px; left:10%; animation-delay:0s;"></div>
    <div class="burbuja" style="width:50px; height:50px; left:25%; animation-delay:2s;"></div>
    <div class="burbuja" style="width:100px; height:100px; left:40%; animation-delay:4s;"></div>
    <div class="burbuja" style="width:60px; height:60px; left:60%; animation-delay:1s;"></div>
    <div class="burbuja" style="width:90px; height:90px; left:75%; animation-delay:3s;"></div>
</div>

<!-- LUCES NEÓN -->
<div class="luz"></div>
<div class="luz"></div>

<!-- CONTENEDOR DEL TRADUCTOR -->
<div class="contenedor">
    <div class="icon">👋</div>
    <h2>Traductor de Palabras a Lenguaje de Señas</h2>
    <textarea placeholder="Escribe una palabra..."></textarea>
    <button class="traducir">Traducir</button>
</div>

</body>
</html>
