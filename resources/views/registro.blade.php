<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registro | Sistema LSM</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body{

            height:100vh;

            display:flex;
            justify-content:center;
            align-items:center;

            background:
            linear-gradient(
                135deg,
                #00b4d8,
                #48cae4,
                #64dfdf,
                #72efdd,
                #80ffdb
            );

            overflow:hidden;
        }

        body::before{

            content:"";

            position:absolute;

            width:500px;
            height:500px;

            background:#7bffef;

            border-radius:50%;

            top:-150px;
            left:-150px;

            opacity:0.35;

            filter:blur(120px);
        }

        body::after{

            content:"";

            position:absolute;

            width:450px;
            height:450px;

            background:#00f5d4;

            border-radius:50%;

            bottom:-150px;
            right:-120px;

            opacity:0.35;

            filter:blur(120px);
        }

        .contenedor{

            position:relative;

            width:950px;

            padding:90px 90px;

            border-radius:40px;

            background:
            rgba(255,255,255,0.22);

            backdrop-filter:blur(25px);

            border:
            2px solid rgba(255,255,255,0.3);

            box-shadow:
            0 20px 60px rgba(0,0,0,0.25);

            text-align:center;

            z-index:10;

            color:#000;
        }

        .icon{

            font-size:110px;

            margin-bottom:25px;

            filter:
            drop-shadow(0 0 25px rgba(0,255,220,0.7));
        }

        h2{

            margin-bottom:45px;

            font-weight:800;

            font-size:48px;

            color:#000;

            text-shadow:none;
        }

        .form-group{

            margin-bottom:30px;

            text-align:left;
        }

        label{

            display:block;

            font-size:22px;

            margin-bottom:10px;

            color:#000;

            font-weight:600;
        }

        input{

            width:100%;

            padding:22px;

            border:none;

            border-radius:18px;

            font-size:20px;

            background:
            rgba(255,255,255,0.92);

            color:#000;

            transition:0.3s;

            box-shadow:
            0 8px 20px rgba(0,0,0,0.12);
        }

        input:focus{

            outline:none;

            transform:scale(1.02);

            box-shadow:
            0 0 0 5px rgba(0,245,212,0.35);
        }

        button{

            width:100%;

            padding:24px;

            margin-top:20px;

            background:#000;

            border:none;

            color:white;

            font-size:24px;

            font-weight:bold;

            border-radius:20px;

            cursor:pointer;

            transition:0.35s;

            box-shadow:
            0 12px 30px rgba(0,0,0,0.35);
        }

        button:hover{

            transform:
            translateY(-5px)
            scale(1.03);

            background:#222;
        }

        a{

            display:block;

            margin-top:35px;

            font-size:20px;

            text-decoration:none;

            color:#000;

            font-weight:600;
        }

        a:hover{

            text-decoration:underline;
        }

        .error{

            margin-top:8px;

            color:#ff0033;

            font-size:16px;

            font-weight:bold;
        }

        .alert{

            background:
            rgba(255,77,109,0.15);

            color:#000;

            padding:15px;

            border-radius:14px;

            margin-bottom:25px;

            text-align:center;

            border:
            1px solid rgba(0,0,0,0.15);
        }

        @media(max-width:1000px){

            .contenedor{

                width:95%;

                padding:50px 30px;
            }

            h2{

                font-size:38px;
            }

            input{

                font-size:18px;
            }
        }

    </style>

</head>

<body>

<div class="contenedor">
    <div class="icon">
        👐
    </div>
    <h2>
        Crear Cuenta
    </h2>
    @if ($errors->any())

        <div class="alert">
            {{ $errors->first() }}
        </div>
    @endif
    <form action="{{ route('registro.guardar') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Nombre</label>
            <input
                type="text"
                name="first_name"
                value="{{ old('first_name') }}"
                autocomplete="given-name"
                required>
            @error('first_name')

                <div class="error">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-group">
            <label>Apellido paterno</label>
            <input
                type="text"
                name="last_name"
                value="{{ old('last_name') }}"
                autocomplete="family-name"
                required>

            @error('last_name')

                <div class="error">
                    {{ $message }}
                </div>

            @enderror
        </div>
        <div class="form-group">
            <label>Apellido materno</label>
            <input
                type="text"
                name="middle_name"
                value="{{ old('middle_name') }}"
                required>
            @error('middle_name')

                <div class="error">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-group">
            <label>Correo electrónico</label>
            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                autocomplete="email"
                required>
            @error('email')

                <div class="error">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-group">
            <label>Contraseña</label>
            <input
                type="password"
                name="password"
                autocomplete="new-password"
                required>
            @error('password')
            <div class="error">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-group">
            <label>Confirmar contraseña</label>
            <input
                type="password"
                name="password_confirmation"
                autocomplete="new-password"
                required>
        </div>
        <button type="submit">

            Registrarse
        </button>
    </form>
    <a href="{{ route('login') }}">
        ¿Ya tienes cuenta? Inicia sesión
    </a>
</div>
</body>
</html>