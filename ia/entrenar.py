from pathlib import Path
import json
import os
import sys

import joblib
import numpy as np
import pymysql
from dotenv import load_dotenv
from sklearn.ensemble import RandomForestClassifier
from sklearn.metrics import accuracy_score, classification_report
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import LabelEncoder

BASE = Path(__file__).resolve().parent
MODELOS = BASE / "modelos"
MODELOS.mkdir(exist_ok=True)

load_dotenv(BASE / ".env")


def normalizar(vector):
    arr = np.asarray(vector, dtype=np.float32).reshape(21, 3)

    # Punto cero = muñeca
    arr = arr - arr[0]

    escala = np.max(np.linalg.norm(arr, axis=1))

    if escala > 1e-8:
        arr = arr / escala

    return arr.flatten()


def conexion():
    return pymysql.connect(
        host=os.getenv("DB_HOST", "127.0.0.1"),
        port=int(os.getenv("DB_PORT", "3306")),
        user=os.getenv("DB_USER", "root"),
        password=os.getenv("DB_PASSWORD", ""),
        database=os.getenv("DB_NAME", "traductor_lsm"),
        charset="utf8mb4",
        cursorclass=pymysql.cursors.DictCursor
    )


def cargar():
    sql = """
    SELECT s.landmarks_json, w.palabra_espanol
    FROM sign_samples s
    INNER JOIN words w ON w.id_palabra = s.id_palabra
    ORDER BY s.id_muestra
    """

    with conexion() as con:
        with con.cursor() as cur:
            cur.execute(sql)
            filas = cur.fetchall()

    X, y = [], []

    for fila in filas:
        try:
            vector = json.loads(fila["landmarks_json"])

            if not isinstance(vector, list) or len(vector) != 63:
                continue

            X.append(normalizar(vector))
            y.append(str(fila["palabra_espanol"]).strip().upper())
        except Exception:
            pass

    return np.asarray(X, dtype=np.float32), np.asarray(y)


def main():
    X, etiquetas = cargar()

    if len(X) < 40:
        raise RuntimeError(
            "No hay suficientes muestras. Captura al menos 80-100 por seña."
        )

    clases, conteos = np.unique(etiquetas, return_counts=True)

    print("\nMuestras por clase:")
    for clase, n in zip(clases, conteos):
        print(f"{clase:25s} {n}")

    if len(clases) < 2:
        raise RuntimeError("Necesitas al menos dos señas distintas.")

    encoder = LabelEncoder()
    y = encoder.fit_transform(etiquetas)

    puede_dividir = np.min(conteos) >= 5 and len(X) >= 80

    if puede_dividir:
        X_train, X_test, y_train, y_test = train_test_split(
            X,
            y,
            test_size=0.20,
            random_state=42,
            stratify=y
        )
    else:
        X_train, y_train = X, y
        X_test, y_test = X, y

    modelo = RandomForestClassifier(
        n_estimators=500,
        max_features="sqrt",
        class_weight="balanced_subsample",
        random_state=42,
        n_jobs=-1
    )

    print("\nEntrenando IA...")
    modelo.fit(X_train, y_train)

    pred = modelo.predict(X_test)

    accuracy = accuracy_score(y_test, pred)

    print(f"\nExactitud: {accuracy * 100:.2f}%")
    print(
        classification_report(
            y_test,
            pred,
            target_names=encoder.classes_,
            zero_division=0
        )
    )

    joblib.dump(modelo, MODELOS / "modelo_lsm.joblib")
    joblib.dump(encoder, MODELOS / "encoder_lsm.joblib")

    (MODELOS / "metadata.json").write_text(
        json.dumps(
            {
                "clases": encoder.classes_.tolist(),
                "muestras": int(len(X)),
                "accuracy": float(accuracy)
            },
            ensure_ascii=False,
            indent=2
        ),
        encoding="utf-8"
    )

    print("\nModelo guardado en ia/modelos/")


if __name__ == "__main__":
    try:
        main()
    except Exception as e:
        print("\nERROR:", e)
        sys.exit(1)
