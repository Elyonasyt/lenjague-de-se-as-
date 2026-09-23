from pathlib import Path
import os

import joblib
import numpy as np
from dotenv import load_dotenv
from flask import Flask, jsonify, request
from flask_cors import CORS

BASE = Path(__file__).resolve().parent
MODELOS = BASE / "modelos"

load_dotenv(BASE / ".env")

app = Flask(__name__)
CORS(app)

modelo = None
encoder = None


def normalizar(vector):
    arr = np.asarray(vector, dtype=np.float32).reshape(21, 3)
    arr = arr - arr[0]

    escala = np.max(np.linalg.norm(arr, axis=1))

    if escala > 1e-8:
        arr = arr / escala

    return arr.flatten()


def cargar_modelo():
    global modelo, encoder

    m = MODELOS / "modelo_lsm.joblib"
    e = MODELOS / "encoder_lsm.joblib"

    if not m.exists() or not e.exists():
        modelo = None
        encoder = None
        return False

    modelo = joblib.load(m)
    encoder = joblib.load(e)

    return True


cargar_modelo()


@app.get("/health")
def health():
    return jsonify({
        "ok": True,
        "modelo_cargado": modelo is not None,
        "clases": encoder.classes_.tolist() if encoder is not None else []
    })


@app.post("/reload")
def reload_model():
    ok = cargar_modelo()
    return jsonify({"ok": ok})


@app.post("/predict")
def predict():
    if modelo is None or encoder is None:
        return jsonify({
            "ok": False,
            "error": "No existe un modelo entrenado."
        }), 503

    data = request.get_json(silent=True) or {}
    vector = data.get("landmarks")

    if not isinstance(vector, list) or len(vector) != 63:
        return jsonify({
            "ok": False,
            "error": "Se requieren exactamente 63 valores."
        }), 422

    x = normalizar(vector).reshape(1, -1)

    probabilidades = modelo.predict_proba(x)[0]
    idx = int(np.argmax(probabilidades))
    clase_codificada = modelo.classes_[idx]

    palabra = encoder.inverse_transform([int(clase_codificada)])[0]
    confianza = float(probabilidades[idx])

    return jsonify({
        "ok": True,
        "palabra": str(palabra),
        "confianza": confianza
    })


if __name__ == "__main__":
    app.run(
        host=os.getenv("IA_HOST", "127.0.0.1"),
        port=int(os.getenv("IA_PORT", "5001")),
        debug=True
    )
