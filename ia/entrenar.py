from pathlib import Path
import json
import sys

import numpy as np
import joblib

from sklearn.ensemble import RandomForestClassifier
from sklearn.model_selection import train_test_split
from sklearn.metrics import accuracy_score


BASE_DIR = Path(__file__).resolve().parent

PROYECTO_DIR = BASE_DIR.parent


DATASET = (
    PROYECTO_DIR
    / "storage"
    / "app"
    / "lsm_dataset"
    / "muestras.jsonl"
)


MODELO = (
    BASE_DIR
    / "modelo_lsm.joblib"
)


print("")
print("===================================")
print("       ENTRENAMIENTO LSM")
print("===================================")
print("")


if not DATASET.exists():

    print(
        "ERROR: No existe el dataset:"
    )

    print(DATASET)

    print("")
    print(
        "Captura muestras primero."
    )

    sys.exit(1)


X = []

y = []


with open(
    DATASET,
    "r",
    encoding="utf-8"
) as archivo:

    for linea in archivo:

        linea = linea.strip()

        if not linea:
            continue


        try:

            registro = json.loads(
                linea
            )


            puntos = registro[
                "puntos"
            ]


            etiqueta = registro[
                "etiqueta"
            ]


            if len(puntos) != 63:

                print(
                    "Muestra ignorada."
                )

                continue


            X.append(
                puntos
            )


            y.append(
                etiqueta
            )


        except Exception as error:

            print(
                "Error leyendo muestra:",
                error
            )


X = np.array(
    X,
    dtype=np.float32
)


y = np.array(y)


if len(X) == 0:

    print(
        "No existen muestras válidas."
    )

    sys.exit(1)


clases = np.unique(y)


print(
    "Cantidad de muestras:",
    len(X)
)


print(
    "Cantidad de señas:",
    len(clases)
)


print(
    "Señas:"
)


for clase in clases:

    cantidad = np.sum(
        y == clase
    )

    print(
        "-",
        clase,
        ":",
        cantidad,
        "muestras"
    )


if len(clases) < 2:

    print("")
    print(
        "Necesitas al menos dos señas diferentes."
    )

    sys.exit(1)


modelo = RandomForestClassifier(

    n_estimators=300,

    random_state=42,

    class_weight="balanced",

    n_jobs=-1

)


try:

    X_train, X_test, y_train, y_test = (
        train_test_split(

            X,

            y,

            test_size=0.20,

            random_state=42,

            stratify=y

        )
    )


    modelo.fit(
        X_train,
        y_train
    )


    predicciones = modelo.predict(
        X_test
    )


    precision = accuracy_score(
        y_test,
        predicciones
    )


    print("")
    print(
        "Exactitud de prueba:",
        round(
            precision * 100,
            2
        ),
        "%"
    )


except ValueError as error:

    print("")
    print(
        "No se pudo hacer la división de prueba."
    )

    print(error)


print("")
print(
    "Entrenando modelo final..."
)


modelo.fit(
    X,
    y
)


joblib.dump(
    modelo,
    MODELO
)


print("")
print("===================================")
print("       MODELO GUARDADO")
print("===================================")

print(MODELO)

print("")
print(
    "Ahora ejecuta:"
)

print("")
print(
    "py api.py"
)