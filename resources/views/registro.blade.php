<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Lenguaje de Señas</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body {
            height: 100vh; display: flex; justify-content: center; align-items: center;
            background: radial-gradient(circle at top left, #ff00cc, #ff8a00 70%);
            position: relative; overflow: hidden;
        }
        .fondo { position: absolute; width: 100%; height: 100%; overflow: hidden; z-index: 0; }
        .burbuja { position: absolute; bottom: -100px; background: rgba(255, 255, 255, 0.15); border-radius: 50%; animation: subir 10s infinite ease-in; }
        @keyframes subir { from { transform: translateY(0) scale(1); opacity: 1; } to { transform: translateY(-110vh) scale(1.3); opacity: 0; } }
        .contenedor {
            position: relative; z-index: 2; width: 400px; padding: 50px 40px; border-radius: 25px;
            background: rgba(255,255,255,0.1); backdrop-filter: blur(25px);
            box-shadow: 0 0 40px rgba(255,50,150,0.5), inset 0 0 20px rgba(255,255,255,0.2);
            text-align: center; color: white; animation: flotar 3s ease-in-out infinite;
        }
        @keyframes flotar { 0%,100%{transform:translateY(0);}50%{transform:translateY(-10px);} }
        .icon { font-size: 80px; margin-bottom:15px; filter: drop-shadow(0 0 10px #ff4dd2); animation: brillo 3s infinite alternate; }
        @keyframes brillo { from{filter: drop-shadow(0 0 5px #ff66cc);} to{filter: drop-shadow(0 0 20px #ff00ff);} }
        h2 { margin-bottom:20px; letter-spacing:2px; font-weight:600; text-shadow:0 0 10px rgba(255,50,200,0.8);}
        input { width: 90%; padding:12px; margin:10px 0; border:none; border-radius:12px; outline:none; background: rgba(255,255,255,0.95); font-size:15px; text-align:center; transition:0.3s; }
        input:focus { transform: scale(1.05); box-shadow:0 0 15px #ff00ff; }
        button { display:inline-block; width:90%; padding:14px; margin-top:15px; border:none; border-radius:12px; background:linear-gradient(90deg,#ff0080,#ff5e00); color:white; font-weight:bold; font-size:16px; cursor:pointer; transition:0.3s; box-shadow:0 0 20px rgba(255,50,200,0.6);}
        button:hover { background:linear-gradient(90deg,#ff5e00,#ff00ff); transform:scale(1.08); box-shadow:0 0 30px rgba(255,0,255,0.9);}
        a { display:block; margin-top:20px; color:#ffd6f0; text-decoration:none; font-size:14px; transition:0.2s; }
        a:hover { text-shadow:0 0 10px #ff00ff; color:#fff; }
        .luz { position:absolute; width:150px; height:150px; background:radial-gradient(circle, rgba(255,0,255,0.3), transparent); border-radius:50%; animation:moverLuz 6s infinite alternate; }
        .luz:nth-child(1) { top:10%; left:10%; animation-delay:0s; }
        .luz:nth-child(2) { bottom:10%; right:15%; animation-delay:3s; }
        @keyframes moverLuz { 0%{transform:translate(0,0);}100%{transform:translate(40px,-40px);} }
        .error { color:#ff4d4d; font-size:14px; margin-top:5px; text-align:left; }
    </style>
</head>
<body>

<div class="fondo">
    <div class="burbuja" style="width:80px; height:80px; left:10%; animation-delay:0s;"></div>
    <div class="burbuja" style="width:50px; height:50px; left:25%; animation-delay:2s;"></div>
    <div class="burbuja" style="width:100px; height:100px; left:40%; animation-delay:4s;"></div>
    <div class="burbuja" style="width:60px; height:60px; left:60%; animation-delay:1s;"></div>
    <div class="burbuja" style="width:90px; height:90px; left:75%; animation-delay:3s;"></div>
</div>

<div class="luz"></div>
<div class="luz"></div>

<div class="contenedor">
    <div class="icon">👐</div>
    <h2>Registro de Usuario</h2>

    <form action="{{ route('registro.post') }}" method="POST">
        @csrf
        <input type="text" name="nombre" placeholder="Nombre completo" value="{{ old('nombre') }}">
        @error('nombre') <div class="error">{{ $message }}</div> @enderror

        <input type="email" name="correo" placeholder="Correo electrónico" value="{{ old('correo') }}">
        @error('correo') <div class="error">{{ $message }}</div> @enderror

        <input type="password" name="contrasena" placeholder="Contraseña">
        @error('contrasena') <div class="error">{{ $message }}</div> @enderror

        <input type="password" name="contrasena_confirmation" placeholder="Confirmar contraseña">

        <button type="submit">Registrarse</button>
    </form>

    <a href="/inicio">¿Ya tienes cuenta? Inicia sesión</a>
</div>

</body>
</html>
