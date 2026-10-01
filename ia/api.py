from pathlib import Path

import numpy as np
import joblib

from flask import Flask
from flask import request
from flask import jsonify

from flask_cors import CORS


BASE_DIR = Path(
    __file__
).resolve().parent


MODELO_PATH = (
    BASE_DIR
    / "modelo_lsm.joblib"
)


app = Flask(__name__)


CORS(app)


modelo = None


def cargar_modelo():

    global modelo


    if not MODELO_PATH.exists():

        print("")
        print(
            "No existe modelo_lsm.joblib"
        )

        print(
            "Ejecuta primero:"
        )

        print("")
        print(
            "py entrenar.py"
        )

        print("")

        modelo = None

        return


    modelo = joblib.load(
        MODELO_PATH
    )


    print("")
    print(
        "Modelo cargado correctamente."
    )

    print(
        "Clases:"
    )

    print(
        modelo.classes_
    )

    print("")


cargar_modelo()


@app.route(
    "/salud",
    methods=["GET"]
)
def salud():

    return jsonify({

        "ok": True,

        "modelo_cargado":
            modelo is not None

    })


@app.route(
    "/predecir",
    methods=["POST"]
)
def predecir():

    if modelo is None:

        return jsonify({

            "ok": False,

            "message":
                "El modelo no está entrenado."

        }), 503


    datos = request.get_json(
        silent=True
    )


    if not datos:

        return jsonify({

            "ok": False,

            "message":
                "No se recibieron datos."

        }), 400


    puntos = datos.get(
        "puntos"
    )


    if puntos is None:

        return jsonify({

            "ok": False,

            "message":
                "No se recibió puntos."

        }), 400


    if len(puntos) != 63:

        return jsonify({

            "ok": False,

            "message":
                "Se necesitan exactamente 63 valores."

        }), 400


    try:

        entrada = np.array(

            puntos,

            dtype=np.float32

        ).reshape(

            1,

            -1

        )


        probabilidades = (
            modelo.predict_proba(
                entrada
            )[0]
        )


        indice = int(
            np.argmax(
                probabilidades
            )
        )


        palabra = str(
            modelo.classes_[
                indice
            ]
        )


        valor_confianza = float(
            probabilidades[
                indice
            ]
        )


        return jsonify({

            "ok":
                True,

            "prediccion":
                palabra,

            "confianza":
                valor_confianza

        })


    except Exception as error:

        print(
            error
        )


        return jsonify({

            "ok":
                False,

            "message":
                str(error)

        }), 500


if __name__ == "__main__":

    print("")
    print(
        "API LSM iniciada."
    )

    print(
        "http://127.0.0.1:5001"
    )

    print("")


    app.run(

        host="127.0.0.1",

        port=5001,

        debug=True

    )