<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Bienvenido | Traductor LSM</title>

<style>

/* =========================
   RESET
========================= */

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

/* =========================
   FONDO GENERAL
========================= */

body{

    min-height:100vh;

    display:flex;
    justify-content:center;
    align-items:center;

    overflow:hidden;

    background:
    linear-gradient(
        135deg,
        #b8fff9,
        #8ef6ff,
        #5ce1e6,
        #48cae4,
        #72efdd
    );

    position:relative;
}

/* =========================
   BURBUJAS DE FONDO
========================= */

body::before{

    content:"";

    position:absolute;

    width:700px;
    height:700px;

    background:#ffffff;

    border-radius:50%;

    top:-250px;
    left:-250px;

    opacity:0.30;

    filter:blur(140px);
}

body::after{

    content:"";

    position:absolute;

    width:650px;
    height:650px;

    background:#7bffef;

    border-radius:50%;

    bottom:-250px;
    right:-250px;

    opacity:0.35;

    filter:blur(140px);
}

/* =========================
   CONTENEDOR PRINCIPAL
========================= */

.welcome-container{

    position:relative;

    width:97%;
    max-width:1450px;

    padding:130px 110px;

    border-radius:60px;

    text-align:center;

    background:
    rgba(255,255,255,0.38);

    backdrop-filter:blur(35px);

    border:
    3px solid rgba(255,255,255,0.60);

    box-shadow:
    0 25px 70px rgba(0,0,0,0.18);

    animation:fadeIn 1s ease;

    z-index:10;
}

/* =========================
   ICONO
========================= */

.icon{

    font-size:190px;

    margin-bottom:45px;

    animation:float 3s ease-in-out infinite;

    filter:
    drop-shadow(0 0 35px rgba(0,255,220,0.9));
}

/* =========================
   TITULO
========================= */

.welcome-container h1{

    font-size:100px;

    font-weight:900;

    line-height:1.15;

    margin-bottom:45px;

    color:#000;

    text-shadow:
    0 0 25px rgba(255,255,255,0.9);
}

/* =========================
   TEXTO
========================= */

.welcome-container p{

    font-size:40px;

    line-height:1.9;

    color:#004e64;

    max-width:1200px;

    margin:auto;

    margin-bottom:75px;
}

/* =========================
   BOTONES
========================= */

.buttons{

    display:flex;

    justify-content:center;

    gap:45px;

    flex-wrap:wrap;
}

.btn{

    text-decoration:none;

    padding:30px 85px;

    border-radius:25px;

    font-size:34px;

    font-weight:bold;

    transition:0.35s;

    letter-spacing:1px;
}

/* =========================
   BOTONES NEGROS
========================= */

.btn-login,
.btn-register{

    background:#000;

    color:white;

    box-shadow:
    0 15px 35px rgba(0,0,0,0.40);
}

.btn-login:hover,
.btn-register:hover{

    transform:
    translateY(-8px)
    scale(1.08);

    background:#222;

    box-shadow:
    0 20px 45px rgba(0,0,0,0.55);
}

/* =========================
   ANIMACIONES
========================= */

@keyframes float{

    0%{
        transform:translateY(0px);
    }

    50%{
        transform:translateY(-18px);
    }

    100%{
        transform:translateY(0px);
    }
}

@keyframes fadeIn{

    from{
        opacity:0;
        transform:translateY(30px);
    }

    to{
        opacity:1;
        transform:translateY(0px);
    }
}

/* =========================
   RESPONSIVE
========================= */

@media(max-width:768px){

    .welcome-container{

        padding:60px 30px;
    }

    .welcome-container h1{

        font-size:55px;
    }

    .welcome-container p{

        font-size:24px;
    }

    .icon{

        font-size:110px;
    }

    .btn{

        width:100%;

        font-size:24px;

        padding:22px 30px;
    }
}

</style>

</head>

<body>

<div class="welcome-container">

    <div class="icon">
        🤟
    </div>

    <h1>
        Traductor de Lenguaje de Señas
    </h1>

    <p>
        Aprende, traduce y comunícate en Lenguaje de Señas
    </p>

    <div class="buttons">

        <a href="/inicio" class="btn btn-login">

            🚀 Iniciar Sesión

        </a>

        <a href="/registro" class="btn btn-register">

            ✨ Registrarse

        </a>

    </div>

</div>

</body>

</html>