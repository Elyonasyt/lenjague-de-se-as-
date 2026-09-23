<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión | LSM</title>
    <style>
        *{box-sizing:border-box;font-family:Arial,sans-serif}
        body{margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#c8fff4,#9bf6ff,#72efdd,#56cfe1)}
        .box{width:min(620px,92%);background:rgba(255,255,255,.55);padding:50px;border-radius:35px;box-shadow:0 20px 50px rgba(0,0,0,.15)}
        .logo{text-align:center;font-size:80px}
        h1{text-align:center;color:#000}
        label{display:block;font-weight:bold;margin:18px 0 8px}
        input{width:100%;padding:16px;border:1px solid #bbb;border-radius:14px;font-size:17px}
        button{width:100%;padding:16px;border:0;border-radius:15px;background:#000;color:#fff;font-size:19px;font-weight:bold;margin-top:25px;cursor:pointer}
        .alert{padding:13px;border-radius:12px;margin-bottom:15px}
        .error{background:#ffe1e1;color:#9b1c1c}
        .success{background:#dcfce7;color:#166534}
        .link{text-align:center;margin-top:20px}
        a{color:#000;font-weight:bold}
    </style>
</head>
<body>
<div class="box">
    <div class="logo">🤟</div>
    <h1>Iniciar sesión</h1>

    @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert error">{{ $errors->first() }}</div>
    @endif

    <form action="{{ route('login.autenticar') }}" method="POST">
        @csrf

        <label>Correo electrónico</label>
        <input type="email" name="email" value="{{ old('email') }}" required>

        <label>Contraseña</label>
        <input type="password" name="password" required>

        <button type="submit">Ingresar</button>
    </form>

    <div class="link">
        ¿No tienes cuenta?
        <a href="{{ route('registro.form') }}">Regístrate aquí</a>
    </div>
</div>
</body>
</html>
