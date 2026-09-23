let recognitionInstance = null;

function activarTexto() {
    document.getElementById('tipoEntrada').value = 'TEXTO';
    document.getElementById('btnMicrofono').style.display = 'none';
    document.getElementById('tituloModo').textContent = 'Traducir texto a LSM';
    document.getElementById('modoTexto').classList.add('activo');
    document.getElementById('modoVoz').classList.remove('activo');
    document.getElementById('estadoVoz').textContent = '';
}

function activarVoz() {
    document.getElementById('tipoEntrada').value = 'VOZ';
    document.getElementById('btnMicrofono').style.display = 'inline-block';
    document.getElementById('tituloModo').textContent = 'Habla y traduce tu voz a LSM';
    document.getElementById('modoVoz').classList.add('activo');
    document.getElementById('modoTexto').classList.remove('activo');
    document.getElementById('estadoVoz').textContent = 'Presiona el micrófono y habla.';
}

function iniciarVoz() {
    const SpeechRecognition =
        window.SpeechRecognition || window.webkitSpeechRecognition;

    if (!SpeechRecognition) {
        alert('Tu navegador no soporta reconocimiento de voz. Prueba Chrome o Edge.');
        return;
    }

    recognitionInstance = new SpeechRecognition();
    recognitionInstance.lang = 'es-MX';
    recognitionInstance.continuous = false;
    recognitionInstance.interimResults = true;

    const textarea = document.getElementById('textoTraductor');
    const estado = document.getElementById('estadoVoz');
    const boton = document.getElementById('btnMicrofono');

    boton.classList.add('escuchando');
    boton.textContent = '🔴 Escuchando...';
    estado.textContent = 'Habla ahora...';

    recognitionInstance.onresult = event => {
        let texto = '';
        for (let i = event.resultIndex; i < event.results.length; i++) {
            texto += event.results[i][0].transcript;
        }
        textarea.value = texto.trim();
        estado.textContent = 'Reconocido: ' + textarea.value;
    };

    recognitionInstance.onerror = event => {
        estado.textContent = 'Error: ' + event.error;
    };

    recognitionInstance.onend = () => {
        boton.classList.remove('escuchando');
        boton.textContent = '🎤 Iniciar micrófono';

        if (textarea.value.trim()) {
            estado.textContent = '✅ Voz capturada. Ahora presiona Traducir.';
        }
    };

    recognitionInstance.start();
}
