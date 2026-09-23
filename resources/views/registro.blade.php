<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | LSM</title>
    <style>
        *{box-sizing:border-box;font-family:Arial,sans-serif}
        body{margin:0;min-height:100vh;padding:30px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#00b4d8,#48cae4,#64dfdf,#72efdd,#80ffdb)}
        .box{width:min(760px,96%);background:rgba(255,255,255,.5);padding:45px;border-radius:35px;box-shadow:0 20px 50px rgba(0,0,0,.15)}
        .logo{text-align:center;font-size:75px}
        h1{text-align:center}
        .grid{display:grid;grid-template-columns:1fr 1fr;gap:15px}
        .full{grid-column:1/-1}
        label{display:block;font-weight:bold;margin:8px 0}
        input{width:100%;padding:14px;border:1px solid #bbb;border-radius:12px;font-size:16px}
        button{width:100%;padding:16px;border:0;border-radius:14px;background:#000;color:#fff;font-size:18px;font-weight:bold;margin-top:18px}
        .error{background:#ffe0e0;color:#991b1b;padding:12px;border-radius:12px;margin-bottom:15px}
        .link{text-align:center;margin-top:18px}
        @media(max-width:650px){.grid{grid-template-columns:1fr}}
    </style>
</head>
<body>
<div class="box">
    <div class="logo">👐</div>
    <h1>Crear cuenta</h1>

    @if($errors->any())
        <div class="error">{{ $errors->first() }}</div>
    @endif

    <form action="{{ route('registro.guardar') }}" method="POST">
        @csrf

        <div class="grid">
            <div>
                <label>Nombre</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}" required>
            </div>

            <div>
                <label>Apellido paterno</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}" required>
            </div>

            <div class="full">
                <label>Apellido materno</label>
                <input type="text" name="middle_name" value="{{ old('middle_name') }}">
            </div>

            <div class="full">
                <label>Correo electrónico</label>
                <input type="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div>
                <label>Contraseña</label>
                <input type="password" name="password" required>
            </div>

            <div>
                <label>Confirmar contraseña</label>
                <input type="password" name="password_confirmation" required>
            </div>
        </div>

        <button type="submit">Registrarse</button>
    </form>

    <div class="link">
        <a href="{{ route('login') }}">Ya tengo una cuenta</a>
    </div>
</div>
</body>
</html>
