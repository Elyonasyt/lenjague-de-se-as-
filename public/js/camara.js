const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const ctx = canvas.getContext('2d');

let ultimosLandmarks = null;
let reconocimientoActivo = false;
let enviando = false;

let ultimaClase = '';
let repeticiones = 0;
let ultimaAgregada = '';
let ultimoAgregado = 0;
let ultimaConfianza = 0;

const CONFIANZA_MINIMA = 0.80;
const REPETICIONES_MINIMAS = 4;
const COOLDOWN_MS = 1400;

function vector63(landmarks) {
    const salida = [];
    for (const p of landmarks) {
        salida.push(Number(p.x), Number(p.y), Number(p.z));
    }
    return salida;
}

const hands = new Hands({
    locateFile: file => `https://cdn.jsdelivr.net/npm/@mediapipe/hands/${file}`
});

hands.setOptions({
    maxNumHands: 1,
    modelComplexity: 1,
    minDetectionConfidence: 0.65,
    minTrackingConfidence: 0.65
});

hands.onResults(results => {
    canvas.width = video.videoWidth || 640;
    canvas.height = video.videoHeight || 480;

    ctx.save();
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    if (results.multiHandLandmarks && results.multiHandLandmarks.length) {
        const puntos = results.multiHandLandmarks[0];
        ultimosLandmarks = vector63(puntos);

        drawConnectors(ctx, puntos, HAND_CONNECTIONS, {lineWidth: 4});
        drawLandmarks(ctx, puntos, {lineWidth: 2, radius: 3});
    } else {
        ultimosLandmarks = null;
        document.getElementById('prediccion').textContent = 'Esperando mano...';
        document.getElementById('confianza').textContent = 'Confianza: 0%';
    }

    ctx.restore();
});

const camera = new Camera(video, {
    onFrame: async () => {
        await hands.send({image: video});
    },
    width: 640,
    height: 480
});

camera.start().catch(err => {
    document.getElementById('estadoReconocimiento').textContent =
        'No se pudo abrir la cámara: ' + err.message;
});

function activarReconocimiento() {
    reconocimientoActivo = true;
    document.getElementById('estadoReconocimiento').textContent =
        'Reconocimiento activo.';
}

function detenerReconocimiento() {
    reconocimientoActivo = false;
    document.getElementById('estadoReconocimiento').textContent =
        'Reconocimiento detenido.';
}

async function predecir() {
    if (!reconocimientoActivo || !ultimosLandmarks || enviando) return;

    enviando = true;

    try {
        const r = await fetch(window.LSM_CONFIG.iaUrl + '/predict', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({landmarks: ultimosLandmarks})
        });

        const data = await r.json();

        if (!r.ok || !data.ok) {
            throw new Error(data.error || 'Error en la IA');
        }

        const palabra = data.palabra || '';
        const confianza = Number(data.confianza || 0);
        ultimaConfianza = confianza;

        document.getElementById('prediccion').textContent = palabra || 'Sin predicción';
        document.getElementById('confianza').textContent =
            'Confianza: ' + (confianza * 100).toFixed(1) + '%';

        if (!palabra || confianza < CONFIANZA_MINIMA) {
            ultimaClase = '';
            repeticiones = 0;
            return;
        }

        if (palabra === ultimaClase) {
            repeticiones++;
        } else {
            ultimaClase = palabra;
            repeticiones = 1;
        }

        const ahora = Date.now();

        if (
            repeticiones >= REPETICIONES_MINIMAS &&
            (palabra !== ultimaAgregada || ahora - ultimoAgregado > COOLDOWN_MS)
        ) {
            agregarPalabra(palabra);
            ultimaAgregada = palabra;
            ultimoAgregado = ahora;
            repeticiones = 0;
        }

    } catch (e) {
        document.getElementById('estadoReconocimiento').textContent =
            'Error IA: ' + e.message + '. Verifica que python api.py esté ejecutándose.';
    } finally {
        enviando = false;
    }
}

setInterval(predecir, 300);

function agregarPalabra(palabra) {
    const frase = document.getElementById('frase');
    const actual = frase.value.trim();
    frase.value = actual ? actual + ' ' + palabra : palabra;
}

function limpiarFrase() {
    document.getElementById('frase').value = '';
    ultimaClase = '';
    ultimaAgregada = '';
    repeticiones = 0;
}

async function guardarFrase() {
    const texto = document.getElementById('frase').value.trim();

    if (!texto) {
        alert('No hay frase para guardar.');
        return;
    }

    const r = await fetch(window.LSM_CONFIG.guardarTraduccionUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.LSM_CONFIG.csrf,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            texto,
            confianza: ultimaConfianza
        })
    });

    const data = await r.json();

    if (!r.ok || !data.ok) {
        alert(data.message || 'No se pudo guardar.');
        return;
    }

    alert('Traducción guardada.');
}

async function capturarLote() {
    const idPalabra = document.getElementById('idPalabra').value;

    if (!idPalabra) {
        alert('Selecciona una palabra.');
        return;
    }

    const boton = document.getElementById('btnCapturar');
    const estado = document.getElementById('estadoCaptura');
    const barra = document.getElementById('barra');

    boton.disabled = true;

    const muestras = [];
    const OBJETIVO = 100;

    estado.textContent = 'Prepárate...';
    await dormir(1000);

    while (muestras.length < OBJETIVO) {
        if (ultimosLandmarks) {
            muestras.push([...ultimosLandmarks]);
            const pct = muestras.length / OBJETIVO * 100;
            barra.style.width = pct + '%';
            estado.textContent = muestras.length + ' / ' + OBJETIVO;
        }

        await dormir(90);
    }

    try {
        estado.textContent = 'Guardando...';

        const r = await fetch(window.LSM_CONFIG.guardarMuestrasUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.LSM_CONFIG.csrf,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                id_palabra: Number(idPalabra),
                muestras
            })
        });

        const data = await r.json();

        if (!r.ok || !data.ok) {
            throw new Error(data.message || 'No se pudieron guardar las muestras.');
        }

        estado.textContent = '✅ ' + data.guardadas + ' muestras guardadas.';
    } catch (e) {
        estado.textContent = '❌ ' + e.message;
    } finally {
        boton.disabled = false;
    }
}

function dormir(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}
