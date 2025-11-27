// Inicializar elementos
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const ctx = canvas.getContext('2d');
const resultado = document.getElementById('resultado');

// Iniciar cámara
async function iniciarCamara() {
    const stream = await navigator.mediaDevices.getUserMedia({ video: true });
    video.srcObject = stream;
}
iniciarCamara();

// === Detección con MediaPipe Hands ===
const hands = new Hands({
    locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/hands/${file}`
});

hands.setOptions({
    maxNumHands: 1,
    modelComplexity: 1,
    minDetectionConfidence: 0.7,
    minTrackingConfidence: 0.7
});

// === Evento: cuando detecta manos ===
hands.onResults(onResults);

const camera = new Camera(video, {
    onFrame: async () => {
        await hands.send({ image: video });
    },
    width: 640,
    height: 480
});
camera.start();

// === Función principal ===
function onResults(results) {
    ctx.save();
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.drawImage(results.image, 0, 0, canvas.width, canvas.height);

    if (results.multiHandLandmarks && results.multiHandLandmarks.length > 0) {
        for (const landmarks of results.multiHandLandmarks) {
            drawConnectors(ctx, landmarks, HAND_CONNECTIONS, { color: "#00FF00", lineWidth: 4 });
            drawLandmarks(ctx, landmarks, { color: "#FF0000", lineWidth: 2 });

            const gesture = reconocerGesto(landmarks);
            resultado.textContent = `Seña detectada: ${gesture}`;

            // (Opcional) Leer con voz
            if (gesture !== "Desconocido") {
                hablar(gesture);
            }
        }
    } else {
        resultado.textContent = "Esperando detección...";
    }
    ctx.restore();
}

// === Reconocimiento básico de gestos ===
// (Estos son ejemplos simples. Se puede entrenar un modelo TensorFlow más avanzado)
function reconocerGesto(landmarks) {
    const yPulgar = landmarks[4].y;
    const yIndice = landmarks[8].y;
    const yMedio = landmarks[12].y;

    if (yPulgar < yIndice && yPulgar < yMedio) return "👍 Pulgar arriba (OK)";
    if (yIndice < yPulgar && yMedio < yPulgar) return "✋ Mano abierta (Hola)";
    if (yIndice > yPulgar && yMedio > yPulgar) return "👊 Puño cerrado (Adiós)";
    return "Desconocido";
}

// === Voz (opcional) ===
function hablar(texto) {
    const msg = new SpeechSynthesisUtterance(texto);
    msg.lang = "es-ES";
    window.speechSynthesis.speak(msg);
}
