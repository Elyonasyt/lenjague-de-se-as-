<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Iniciar Sesión | Traductor LSM</title>

<style>

/* =========================
   GENERAL
========================= */

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{

    height:100vh;

    display:flex;

    justify-content:center;

    align-items:center;

    overflow:hidden;

    background:
    linear-gradient(
        135deg,
        #c8fff4,
        #9bf6ff,
        #72efdd,
        #64dfdf,
        #56cfe1
    );

    position:relative;
}

/* =========================
   EFECTOS FONDO
========================= */

body::before{

    content:"";

    position:absolute;

    width:650px;
    height:650px;

    background:white;

    border-radius:50%;

    top:-250px;
    left:-250px;

    opacity:0.25;

    filter:blur(130px);
}

body::after{

    content:"";

    position:absolute;

    width:600px;
    height:600px;

    background:#7bffef;

    border-radius:50%;

    bottom:-250px;
    right:-250px;

    opacity:0.30;

    filter:blur(130px);
}

/* =========================
   LOGIN CONTAINER
========================= */

.login-container{

    position:relative;

    width:900px;

    background:
    rgba(255,255,255,0.35);

    backdrop-filter:blur(25px);

    padding:90px 80px;

    border-radius:40px;

    border:
    3px solid rgba(255,255,255,0.5);

    box-shadow:
    0 25px 60px rgba(0,0,0,0.18);

    z-index:10;
}

/* =========================
   LOGO
========================= */

.logo{

    text-align:center;

    margin-bottom:55px;
}

.logo span{

    font-size:120px;

    display:block;

    margin-bottom:20px;
}

.logo h1{

    font-size:50px;

    color:#000;

    font-weight:800;

    text-shadow:
    0 0 10px rgba(0,0,0,0.15);
}

/* =========================
   TITULO
========================= */

h2{

    text-align:center;

    margin-bottom:50px;

    color:#000;

    font-weight:700;

    font-size:42px;
}

/* =========================
   FORMULARIO
========================= */

.form-group{

    margin-bottom:35px;
}

label{

    display:block;

    font-size:24px;

    margin-bottom:14px;

    color:#000;

    font-weight:600;
}

input{

    width:100%;

    padding:24px;

    border:
    2px solid rgba(0,0,0,0.15);

    border-radius:20px;

    font-size:24px;

    background:
    rgba(255,255,255,0.95);

    color:#000;

    transition:0.3s;
}

input:focus{

    border-color:#000;

    outline:none;

    box-shadow:
    0 0 0 6px rgba(0,0,0,0.10);
}

/* =========================
   BOTON NEGRO
========================= */

.btn-login{

    width:100%;

    margin-top:20px;

    padding:22px;

    border:none;

    border-radius:22px;

    background:#000;

    color:white;

    font-size:28px;

    font-weight:bold;

    cursor:pointer;

    transition:0.35s;

    box-shadow:
    0 10px 25px rgba(0,0,0,0.35);
}

.btn-login:hover{

    transform:
    translateY(-4px)
    scale(1.02);

    background:#222;
}

/* =========================
   LINK REGISTRO
========================= */

.register-link{

    text-align:center;

    margin-top:40px;

    font-size:24px;

    color:#000;
}

.register-link a{

    color:#000;

    text-decoration:none;

    font-weight:bold;
}

.register-link a:hover{

    text-decoration:underline;
}

/* =========================
   ERROR
========================= */

.error{

    color:#dc2626;

    font-size:18px;

    margin-top:10px;
}

.alert{

    background:
    rgba(255,0,0,0.10);

    color:#991b1b;

    padding:18px;

    border-radius:15px;

    margin-bottom:30px;

    text-align:center;

    font-size:20px;
}

/* =========================
   RESPONSIVE
========================= */

@media(max-width:768px){

    .login-container{

        width:95%;

        padding:50px 30px;
    }

    .logo span{

        font-size:80px;
    }

    .logo h1{

        font-size:32px;
    }

    h2{

        font-size:30px;
    }

    label{

        font-size:20px;
    }

    input{

        font-size:20px;

        padding:18px;
    }

    .btn-login{

        font-size:22px;
    }

    .register-link{

        font-size:18px;
    }
}

</style>

</head>

<body>
<div class="login-container">
    <div class="logo">
        <span>🤟</span>
        <h1> Traductor Lenguaje de Señas </h1>
    </div>
    <h2>    Iniciar Sesión</h2>
    @if ($errors->any())
        <div class="alert"> {{ $errors->first() }} </div>
    @endif
    <form action="{{ route('login.autenticar') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="email"> Correo electrónico</label>
            <input
                type="email"
                id="email"
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
            <label for="password">  Contraseña</label>
            <input
                type="password"
                id="password"
                name="password"
                autocomplete="current-password"
                required>
            @error('password')
               <div class="error"> {{ $message }}
                </div>
            @enderror
        </div>
        <button type="submit" class="btn-login"> 🚀 Ingresar </button>
    </form>
    <div class="register-link">
        ¿No tienes cuenta?
        <a href="{{ route('registro.form') }}">
            Regístrate aquí
        </a>
    </div>
</div>

</body>

</html>