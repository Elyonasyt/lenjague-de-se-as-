// =======================================================
// ELEMENTOS HTML
// =======================================================

const video =
    document.getElementById("video");

const canvas =
    document.getElementById("canvas");

const ctx =
    canvas.getContext("2d");

const prediccion =
    document.getElementById("prediccion");

const confianza =
    document.getElementById("confianza");

const frase =
    document.getElementById("frase");

const estadoReconocimiento =
    document.getElementById(
        "estadoReconocimiento"
    );

const estadoCamara =
    document.getElementById(
        "estadoCamara"
    );

const idPalabra =
    document.getElementById(
        "idPalabra"
    );

const barra =
    document.getElementById(
        "barra"
    );

const estadoCaptura =
    document.getElementById(
        "estadoCaptura"
    );

const btnCapturar =
    document.getElementById(
        "btnCapturar"
    );


// =======================================================
// VARIABLES
// =======================================================

let stream = null;

let hands = null;

let reconocimientoActivo = false;

let procesandoFrame = false;

let esperandoIA = false;

let capturando = false;

let muestras = [];

let ultimoMuestreo = 0;


// Predicciones

let prediccionAnterior = "";

let repeticionesPrediccion = 0;

let ultimaSeñaAgregada = "";

let bloqueoSeña = false;

let sinManoDesde = null;


// Configuración

const TOTAL_MUESTRAS = 100;

const INTERVALO_MUESTRA = 100;

const CONFIANZA_MINIMA = 0.75;

const REPETICIONES_NECESARIAS = 3;


// =======================================================
// INICIALIZAR MEDIAPIPE
// =======================================================

function inicializarMediaPipe() {

    if (hands) {

        return;

    }


    hands = new Hands({

        locateFile: function(file) {

            return (
                "https://cdn.jsdelivr.net/npm/" +
                "@mediapipe/hands/" +
                file
            );

        }

    });


    hands.setOptions({

        maxNumHands: 1,

        modelComplexity: 1,

        minDetectionConfidence:
            0.65,

        minTrackingConfidence:
            0.65

    });


    hands.onResults(
        procesarResultadosMediaPipe
    );

}


// =======================================================
// ENCENDER CÁMARA
// =======================================================

async function iniciarCamara() {

    if (stream) {

        return true;

    }


    try {

        estadoCamara.innerHTML =
            "⏳ Solicitando permiso de cámara...";


        stream =
            await navigator
                .mediaDevices
                .getUserMedia({

                    video: {

                        width: {
                            ideal: 1280
                        },

                        height: {
                            ideal: 720
                        },

                        facingMode:
                            "user"

                    },

                    audio: false

                });


        video.srcObject =
            stream;


        await video.play();


        canvas.width =
            video.videoWidth || 640;


        canvas.height =
            video.videoHeight || 480;


        estadoCamara.innerHTML =
            "✅ Cámara encendida";


        return true;

    }

    catch (error) {

        console.error(
            "Error cámara:",
            error
        );


        stream = null;


        if (
            error.name ===
            "NotAllowedError"
        ) {

            estadoCamara.innerHTML =
                "❌ Debes permitir el acceso a la cámara.";

        }

        else if (
            error.name ===
            "NotFoundError"
        ) {

            estadoCamara.innerHTML =
                "❌ No se encontró ninguna cámara.";

        }

        else {

            estadoCamara.innerHTML =
                "❌ No se pudo iniciar la cámara.";

        }


        return false;

    }

}


// =======================================================
// ACTIVAR RECONOCIMIENTO
// =======================================================

async function activarReconocimiento() {

    inicializarMediaPipe();


    const camaraLista =
        await iniciarCamara();


    if (!camaraLista) {

        return;

    }


    reconocimientoActivo =
        true;


    estadoReconocimiento.innerHTML =
        "🟢 Reconocimiento activo";


    procesarFrame();

}


// =======================================================
// PROCESAR VIDEO
// =======================================================

async function procesarFrame() {

    if (!reconocimientoActivo) {

        return;

    }


    if (
        video.readyState >= 2 &&
        !procesandoFrame
    ) {

        procesandoFrame =
            true;


        try {

            await hands.send({

                image: video

            });

        }

        catch (error) {

            console.error(
                error
            );

        }

        finally {

            procesandoFrame =
                false;

        }

    }


    requestAnimationFrame(
        procesarFrame
    );

}


// =======================================================
// RESULTADOS DE MEDIAPIPE
// =======================================================

function procesarResultadosMediaPipe(
    results
) {

    ctx.clearRect(

        0,

        0,

        canvas.width,

        canvas.height

    );


    if (
        !results.multiHandLandmarks ||
        results.multiHandLandmarks.length === 0
    ) {

        cuandoNoHayMano();

        return;

    }


    sinManoDesde = null;


    const landmarks =
        results.multiHandLandmarks[0];


    /*
     * Dibujar conexiones.
     */

    drawConnectors(

        ctx,

        landmarks,

        HAND_CONNECTIONS,

        {

            lineWidth: 4

        }

    );


    /*
     * Dibujar puntos.
     */

    drawLandmarks(

        ctx,

        landmarks,

        {

            lineWidth: 2,

            radius: 4

        }

    );


    /*
     * Convertir los 21 puntos
     * a 63 números.
     */

    const puntos =
        normalizarLandmarks(
            landmarks
        );


    /*
     * Si estamos entrenando.
     */

    procesarCaptura(
        puntos
    );


    /*
     * Si estamos reconociendo.
     */

    if (
        reconocimientoActivo &&
        !esperandoIA
    ) {

        enviarAIA(
            puntos
        );

    }

}


// =======================================================
// NORMALIZAR 21 PUNTOS
// =======================================================

function normalizarLandmarks(
    landmarks
) {

    const base =
        landmarks[0];


    let puntosRelativos = [];


    let escala = 0;


    for (
        let i = 0;
        i < landmarks.length;
        i++
    ) {

        const dx =
            landmarks[i].x -
            base.x;


        const dy =
            landmarks[i].y -
            base.y;


        const dz =
            landmarks[i].z -
            base.z;


        const distancia =
            Math.sqrt(

                dx * dx +

                dy * dy +

                dz * dz

            );


        if (
            distancia > escala
        ) {

            escala =
                distancia;

        }


        puntosRelativos.push({

            x: dx,

            y: dy,

            z: dz

        });

    }


    if (escala === 0) {

        escala = 1;

    }


    let salida = [];


    puntosRelativos.forEach(

        function(punto) {

            salida.push(
                punto.x / escala
            );

            salida.push(
                punto.y / escala
            );

            salida.push(
                punto.z / escala
            );

        }

    );


    return salida;

}


// =======================================================
// NO HAY MANO
// =======================================================

function cuandoNoHayMano() {

    prediccion.innerHTML =
        "Esperando mano...";


    confianza.innerHTML =
        "Confianza: 0%";


    if (!sinManoDesde) {

        sinManoDesde =
            Date.now();

    }


    if (
        Date.now() -
        sinManoDesde >
        600
    ) {

        bloqueoSeña =
            false;


        prediccionAnterior =
            "";


        repeticionesPrediccion =
            0;

    }

}


// =======================================================
// ENVIAR A PYTHON
// =======================================================

async function enviarAIA(
    puntos
) {

    esperandoIA =
        true;


    try {

        const respuesta =
            await fetch(

                window
                    .LSM_CONFIG
                    .iaUrl
                +
                "/predecir",

                {

                    method:
                        "POST",

                    headers: {

                        "Content-Type":
                            "application/json"

                    },

                    body:
                        JSON.stringify({

                            puntos:
                                puntos

                        })

                }

            );


        const data =
            await respuesta.json();


        if (
            !respuesta.ok ||
            !data.ok
        ) {

            throw new Error(

                data.message ||
                "La IA todavía no está disponible."

            );

        }


        const porcentaje =
            Math.round(

                data.confianza
                *
                100

            );


        prediccion.innerHTML =
            data.prediccion;


        confianza.innerHTML =
            "Confianza: "
            +
            porcentaje
            +
            "%";


        evaluarPrediccion(

            data.prediccion,

            data.confianza

        );

    }

    catch (error) {

        prediccion.innerHTML =
            "IA desconectada";


        confianza.innerHTML =
            "Ejecuta: py api.py";


        console.error(
            error
        );

    }

    finally {

        setTimeout(

            function() {

                esperandoIA =
                    false;

            },

            250

        );

    }

}


// =======================================================
// EVALUAR PREDICCIÓN
// =======================================================

function evaluarPrediccion(
    palabra,
    valorConfianza
) {

    if (
        valorConfianza <
        CONFIANZA_MINIMA
    ) {

        prediccionAnterior =
            "";


        repeticionesPrediccion =
            0;


        return;

    }


    if (
        palabra ===
        prediccionAnterior
    ) {

        repeticionesPrediccion++;

    }

    else {

        prediccionAnterior =
            palabra;


        repeticionesPrediccion =
            1;

    }


    if (
        repeticionesPrediccion <
        REPETICIONES_NECESARIAS
    ) {

        return;

    }


    if (
        bloqueoSeña &&
        palabra ===
        ultimaSeñaAgregada
    ) {

        return;

    }


    agregarPalabraAFrase(
        palabra
    );


    ultimaSeñaAgregada =
        palabra;


    bloqueoSeña =
        true;


    repeticionesPrediccion =
        0;

}


// =======================================================
// AGREGAR PALABRA AL TEXTAREA
// =======================================================

function agregarPalabraAFrase(
    palabra
) {

    const textoActual =
        frase.value.trim();


    if (
        textoActual === ""
    ) {

        frase.value =
            palabra;

    }

    else {

        frase.value =
            textoActual
            +
            " "
            +
            palabra;

    }


    estadoReconocimiento.innerHTML =
        "✅ Seña detectada: "
        +
        palabra;

}


// =======================================================
// DETENER
// =======================================================

function detenerReconocimiento() {

    reconocimientoActivo =
        false;


    capturando =
        false;


    if (stream) {

        stream
            .getTracks()
            .forEach(

                function(track) {

                    track.stop();

                }

            );


        stream =
            null;


        video.srcObject =
            null;

    }


    ctx.clearRect(

        0,

        0,

        canvas.width,

        canvas.height

    );


    prediccion.innerHTML =
        "Esperando mano...";


    confianza.innerHTML =
        "Confianza: 0%";


    estadoCamara.innerHTML =
        "🔴 Cámara apagada";


    estadoReconocimiento.innerHTML =
        "Reconocimiento detenido.";

}


// =======================================================
// CAPTURAR 100 MUESTRAS
// =======================================================

async function capturarLote() {

    if (
        idPalabra.value === ""
    ) {

        alert(
            "Selecciona primero una palabra o letra."
        );

        return;

    }


    await activarReconocimiento();


    if (!stream) {

        return;

    }


    muestras = [];


    capturando =
        true;


    ultimoMuestreo =
        0;


    barra.style.width =
        "0%";


    estadoCaptura.innerHTML =
        "0 / "
        +
        TOTAL_MUESTRAS;


    btnCapturar.disabled =
        true;


    btnCapturar.innerHTML =
        "📸 Capturando...";


    estadoReconocimiento.innerHTML =
        "✋ Mantén la seña frente a la cámara.";

}


// =======================================================
// PROCESAR MUESTRAS
// =======================================================

function procesarCaptura(
    puntos
) {

    if (!capturando) {

        return;

    }


    const ahora =
        Date.now();


    if (
        ahora -
        ultimoMuestreo <
        INTERVALO_MUESTRA
    ) {

        return;

    }


    ultimoMuestreo =
        ahora;


    muestras.push(
        [...puntos]
    );


    const cantidad =
        muestras.length;


    const porcentaje =
        (
            cantidad /
            TOTAL_MUESTRAS
        )
        *
        100;


    barra.style.width =
        porcentaje
        +
        "%";


    estadoCaptura.innerHTML =
        cantidad
        +
        " / "
        +
        TOTAL_MUESTRAS;


    if (
        cantidad >=
        TOTAL_MUESTRAS
    ) {

        capturando =
            false;


        guardarMuestras();

    }

}


// =======================================================
// GUARDAR MUESTRAS EN LARAVEL
// =======================================================

async function guardarMuestras() {

    estadoCaptura.innerHTML =
        "Guardando...";


    try {

        const respuesta =
            await fetch(

                window
                    .LSM_CONFIG
                    .guardarMuestrasUrl,

                {

                    method:
                        "POST",

                    headers: {

                        "Content-Type":
                            "application/json",

                        "X-CSRF-TOKEN":
                            window
                                .LSM_CONFIG
                                .csrf,

                        "Accept":
                            "application/json"

                    },

                    body:
                        JSON.stringify({

                            id_palabra:
                                idPalabra.value,

                            muestras:
                                muestras

                        })

                }

            );


        const data =
            await respuesta.json();


        if (!respuesta.ok) {

            throw new Error(

                data.message ||
                "No se pudieron guardar las muestras."

            );

        }


        barra.style.width =
            "100%";


        estadoCaptura.innerHTML =
            "✅ 100 / 100 guardadas";


        estadoReconocimiento.innerHTML =
            "✅ Muestras guardadas para "
            +
            data.palabra;

    }

    catch (error) {

        console.error(
            error
        );


        estadoCaptura.innerHTML =
            "❌ Error";


        estadoReconocimiento.innerHTML =
            "❌ "
            +
            error.message;

    }

    finally {

        btnCapturar.disabled =
            false;


        btnCapturar.innerHTML =
            "📸 Capturar 100 muestras";

    }

}


// =======================================================
// GUARDAR FRASE
// =======================================================

async function guardarFrase() {

    const texto =
        frase.value.trim();


    if (
        texto === ""
    ) {

        alert(
            "No existe ninguna frase para guardar."
        );

        return;

    }


    try {

        const respuesta =
            await fetch(

                window
                    .LSM_CONFIG
                    .guardarTraduccionUrl,

                {

                    method:
                        "POST",

                    headers: {

                        "Content-Type":
                            "application/json",

                        "X-CSRF-TOKEN":
                            window
                                .LSM_CONFIG
                                .csrf,

                        "Accept":
                            "application/json"

                    },

                    body:
                        JSON.stringify({

                            frase:
                                texto

                        })

                }

            );


        const data =
            await respuesta.json();


        if (!respuesta.ok) {

            throw new Error(

                data.message ||
                "No se pudo guardar."

            );

        }


        estadoReconocimiento.innerHTML =
            "💾 Frase guardada en el historial.";

    }

    catch (error) {

        console.error(
            error
        );


        estadoReconocimiento.innerHTML =
            "❌ "
            +
            error.message;

    }

}


// =======================================================
// LIMPIAR FRASE
// =======================================================

function limpiarFrase() {

    frase.value =
        "";


    prediccionAnterior =
        "";


    ultimaSeñaAgregada =
        "";


    repeticionesPrediccion =
        0;


    bloqueoSeña =
        false;


    estadoReconocimiento.innerHTML =
        "Frase limpiada.";

}


// =======================================================
// CERRAR CÁMARA
// =======================================================

window.addEventListener(

    "beforeunload",

    function() {

        if (stream) {

            stream
                .getTracks()
                .forEach(

                    function(track) {

                        track.stop();

                    }

                );

        }

    }

);