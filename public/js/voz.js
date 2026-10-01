// ====================================================
// VARIABLES GENERALES
// ====================================================

let reconocimiento = null;

let escuchando = false;

let textoAntesDeHablar = "";

let textoFinalSesion = "";



// ====================================================
// OBTENER ELEMENTOS HTML
// ====================================================

const textoTraductor =
    document.getElementById("textoTraductor");


const btnMicrofono =
    document.getElementById("btnMicrofono");


const estadoVoz =
    document.getElementById("estadoVoz");


const tipoEntrada =
    document.getElementById("tipoEntrada");


const modoTexto =
    document.getElementById("modoTexto");


const modoVoz =
    document.getElementById("modoVoz");


const tituloModo =
    document.getElementById("tituloModo");


const informacionVoz =
    document.getElementById("informacionVoz");



// ====================================================
// ACTIVAR MODO TEXTO
// ====================================================

function activarTexto() {

    // Detener micrófono si está funcionando
    detenerVoz();


    // Activar botón Texto
    modoTexto.classList.add("activo");


    // Desactivar botón Voz
    modoVoz.classList.remove("activo");


    // Cambiar título
    tituloModo.textContent =
        "Traducir texto a LSM";


    // Indicar que el tipo de entrada es texto
    tipoEntrada.value =
        "TEXTO";


    // Ocultar botón de micrófono
    btnMicrofono.style.display =
        "none";


    // Ocultar información de voz
    informacionVoz.style.display =
        "none";


    // Cambiar placeholder
    textoTraductor.placeholder =
        "Escribe una frase...";


    // Limpiar mensaje
    estadoVoz.textContent =
        "";


    // Colocar cursor en textarea
    textoTraductor.focus();
}



// ====================================================
// ACTIVAR MODO VOZ
// ====================================================

function activarVoz() {

    // Activar botón Voz
    modoVoz.classList.add("activo");


    // Desactivar botón Texto
    modoTexto.classList.remove("activo");


    // Cambiar título
    tituloModo.textContent =
        "Traducir voz a LSM";


    // Cambiar tipo de entrada
    tipoEntrada.value =
        "VOZ";


    // Mostrar botón del micrófono
    btnMicrofono.style.display =
        "inline-block";


    // Mostrar información
    informacionVoz.style.display =
        "block";


    // Cambiar placeholder
    textoTraductor.placeholder =
        "Presiona el micrófono y comienza a hablar...";


    // Mostrar mensaje
    estadoVoz.textContent =
        "🎤 Micrófono listo para utilizarse";
}



// ====================================================
// CREAR RECONOCIMIENTO DE VOZ
// ====================================================

function crearReconocimiento() {

    // Chrome utiliza webkitSpeechRecognition
    // Algunos navegadores pueden utilizar SpeechRecognition

    const SpeechRecognition =
        window.SpeechRecognition ||
        window.webkitSpeechRecognition;


    // Verificar compatibilidad
    if (!SpeechRecognition) {

        alert(
            "Tu navegador no admite reconocimiento de voz. " +
            "Utiliza Google Chrome o Microsoft Edge."
        );

        return null;
    }


    // Crear reconocimiento
    const recognition =
        new SpeechRecognition();


    // Idioma español de México
    recognition.lang =
        "es-MX";


    // Permitir reconocimiento continuo
    recognition.continuous =
        true;


    // Mostrar texto mientras se está hablando
    recognition.interimResults =
        true;


    // Una alternativa de reconocimiento
    recognition.maxAlternatives =
        1;


    return recognition;
}



// ====================================================
// INICIAR MICRÓFONO
// ====================================================

function iniciarVoz() {

    // Si ya está escuchando,
    // el mismo botón sirve para detenerlo

    if (escuchando) {

        detenerVoz();

        return;
    }


    // Crear reconocimiento
    reconocimiento =
        crearReconocimiento();


    if (!reconocimiento) {

        return;
    }


    // Guardar texto que ya existe
    textoAntesDeHablar =
        textoTraductor.value.trim();


    // Reiniciar texto de esta sesión
    textoFinalSesion =
        "";


    // =================================================
    // CUANDO COMIENZA EL MICRÓFONO
    // =================================================

    reconocimiento.onstart =
        function () {

            escuchando =
                true;


            // Cambiar color del botón
            btnMicrofono.classList.add(
                "escuchando"
            );


            // Cambiar texto del botón
            btnMicrofono.innerHTML =
                "⏹ Detener micrófono";


            // Mostrar estado
            estadoVoz.innerHTML =
                "🟢 Escuchando... habla ahora";


            // Cambiar tipo
            tipoEntrada.value =
                "VOZ";


            textoTraductor.focus();
        };



    // =================================================
    // CUANDO DETECTA LA VOZ
    // =================================================

    reconocimiento.onresult =
        function (event) {

            let textoTemporal = "";


            // Recorrer resultados
            for (
                let i = event.resultIndex;
                i < event.results.length;
                i++
            ) {


                let frase =
                    event.results[i][0].transcript;


                // Quitar espacios extras
                frase =
                    frase.trim();


                // Si el resultado ya es final
                if (
                    event.results[i].isFinal
                ) {

                    textoFinalSesion +=
                        frase + " ";

                }

                // Resultado mientras todavía habla
                else {

                    textoTemporal +=
                        frase + " ";

                }
            }



            // =========================================
            // CONSTRUIR TEXTO
            // =========================================

            let nuevoTexto = "";


            // Texto que ya existía
            if (
                textoAntesDeHablar !== ""
            ) {

                nuevoTexto +=
                    textoAntesDeHablar + " ";
            }


            // Texto ya confirmado por voz
            nuevoTexto +=
                textoFinalSesion;


            // Texto temporal que está hablando
            nuevoTexto +=
                textoTemporal;



            // =========================================
            // ESCRIBIR AUTOMÁTICAMENTE EN TEXTAREA
            // =========================================

            textoTraductor.value =
                nuevoTexto.trim();



            // Colocar cursor al final
            colocarCursorAlFinal();



            // Mover textarea hacia abajo
            textoTraductor.scrollTop =
                textoTraductor.scrollHeight;
        };



    // =================================================
    // ERROR DE RECONOCIMIENTO
    // =================================================

    reconocimiento.onerror =
        function (event) {


            console.error(
                "Error de reconocimiento:",
                event.error
            );


            switch (
                event.error
            ) {


                case "not-allowed":

                    estadoVoz.innerHTML =
                        "❌ No se permitió utilizar el micrófono.";

                    break;



                case "service-not-allowed":

                    estadoVoz.innerHTML =
                        "❌ El servicio de reconocimiento de voz está bloqueado.";

                    break;



                case "no-speech":

                    estadoVoz.innerHTML =
                        "⚠️ No se detectó voz. Habla cerca del micrófono.";

                    break;



                case "audio-capture":

                    estadoVoz.innerHTML =
                        "❌ No se encontró ningún micrófono.";

                    break;



                case "network":

                    estadoVoz.innerHTML =
                        "❌ Error de red en el reconocimiento de voz.";

                    break;



                case "aborted":

                    estadoVoz.innerHTML =
                        "⏹ Reconocimiento detenido.";

                    break;



                default:

                    estadoVoz.innerHTML =
                        "❌ Error al reconocer la voz: "
                        + event.error;

                    break;
            }
        };



    // =================================================
    // CUANDO TERMINA
    // =================================================

    reconocimiento.onend =
        function () {


            escuchando =
                false;


            btnMicrofono.classList.remove(
                "escuchando"
            );


            btnMicrofono.innerHTML =
                "🎤 Iniciar micrófono";


            if (
                textoTraductor.value.trim() !== ""
            ) {

                estadoVoz.innerHTML =
                    "✅ Voz capturada. Ahora puedes presionar Traducir.";

            }

        };



    // =================================================
    // INICIAR RECONOCIMIENTO
    // =================================================

    try {

        reconocimiento.start();

    }

    catch (error) {

        console.error(
            "No fue posible iniciar el micrófono:",
            error
        );


        estadoVoz.innerHTML =
            "❌ No se pudo iniciar el micrófono.";
    }

}



// ====================================================
// DETENER MICRÓFONO
// ====================================================

function detenerVoz() {

    if (
        reconocimiento &&
        escuchando
    ) {

        try {

            reconocimiento.stop();

        }

        catch (error) {

            console.log(
                "Micrófono detenido."
            );
        }
    }


    escuchando =
        false;


    if (btnMicrofono) {

        btnMicrofono.classList.remove(
            "escuchando"
        );


        btnMicrofono.innerHTML =
            "🎤 Iniciar micrófono";
    }
}



// ====================================================
// LIMPIAR TEXTO
// ====================================================

function limpiarTexto() {

    // Detener voz
    detenerVoz();


    // Limpiar textarea
    textoTraductor.value =
        "";


    // Reiniciar variables
    textoAntesDeHablar =
        "";


    textoFinalSesion =
        "";


    // Limpiar estado
    estadoVoz.innerHTML =
        "";


    // Colocar cursor
    textoTraductor.focus();
}



// ====================================================
// COLOCAR CURSOR AL FINAL
// ====================================================

function colocarCursorAlFinal() {

    const longitud =
        textoTraductor.value.length;


    textoTraductor.focus();


    textoTraductor.setSelectionRange(
        longitud,
        longitud
    );
}



// ====================================================
// DETENER MICRÓFONO AL SALIR
// ====================================================

window.addEventListener(
    "beforeunload",
    function () {

        detenerVoz();

    }
);