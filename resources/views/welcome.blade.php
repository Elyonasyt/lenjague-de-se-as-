<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traductor LSM</title>
    <style>
        *{box-sizing:border-box;font-family:Arial,sans-serif}
        body{margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#fff,#fff,#fff,#fff,#fff)}
        .card{width:min(1200px,94%);padding:80px 50px;border-radius:45px;background:rgba(254, 254, 254, 0.45);text-align:center;box-shadow:0 25px 60px rgba(0,0,0,.15)}
        .icon{font-size:120px}
        h1{font-size:64px;margin:20px 0;color:#000}
        p{font-size:26px;color:#045}
        .buttons{display:flex;gap:20px;justify-content:center;flex-wrap:wrap;margin-top:45px}
        a{padding:20px 45px;background:#000;color:#fff;text-decoration:none;border-radius:18px;font-size:22px;font-weight:bold}
        @media(max-width:700px){h1{font-size:40px}.icon{font-size:80px}.card{padding:50px 25px}}
    </style>
</head>
<body>
<div class="card">
    <div class="icon">🤟</div>
    <h1>Sistema inteigente para traducir lengua de señas</h1>
    <p>Texto, voz y reconocimiento de señas con cámara e inteligencia artificial.</p>
    <div class="buttons">
        <a href="{{ route('login') }}">🚀 Iniciar sesión</a>
        <a href="{{ route('registro.form') }}">✨ Registrarse</a>
    </div>
</div>
</body>
</html>
