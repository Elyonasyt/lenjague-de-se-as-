# PROYECTO COMPLETO - TRADUCTOR LSM

Este paquete contiene los archivos necesarios para integrar:

- Registro e inicio de sesión
- Usuario normal y administrador
- Traductor de texto
- Traductor por voz
- Cámara con MediaPipe Hands
- Captura de muestras para entrenar IA
- IA Python con Random Forest
- Historial
- Bitácora y auditoría
- CRUD de usuarios
- CRUD de categorías
- CRUD de palabras
- CRUD de imágenes
- CRUD de traducciones
- Panel para ver muestras de IA

## 1. Crear un proyecto Laravel

Si todavía no tienes uno:

```bash
composer create-project laravel/laravel traductor-lsm
cd traductor-lsm
```

Si ya tienes tu proyecto, no lo recrees: copia los archivos de este paquete.

## 2. Base de datos

En phpMyAdmin importa:

`database/traductor_lsm_completo.sql`

IMPORTANTE: el SQL borra y vuelve a crear las tablas.
Si tienes datos valiosos, haz respaldo antes.

## 3. Copiar archivos

Copia:

- app/Http/Controllers/*
- resources/views/*
- public/js/*
- public/images/signs/no-image.svg
- routes/web.php

a las mismas rutas de tu proyecto Laravel.

## 4. Configurar .env

Usa `.env.laravel.example` como referencia.

Después ejecuta:

```bash
php artisan key:generate
php artisan config:clear
php artisan cache:clear
```

## 5. Iniciar Laravel

```bash
php artisan serve
```

Abre:

http://127.0.0.1:8000

## 6. Administrador

El SQL incluye un administrador inicial:

Correo:

admin@lsm.local

Contraseña:

Admin123*

Si ese hash no funciona en tu versión de PHP/Laravel, crea una cuenta normal
desde `/registro` y cambia su rol a ADMIN desde phpMyAdmin:

```sql
UPDATE users
SET role='ADMIN'
WHERE email='TU_CORREO';
```

## 7. Cargar palabras e imágenes

Entra a:

http://127.0.0.1:8000/admin

Agrega:

1. categoría
2. palabra
3. imagen de la seña

Cada imagen debe estar relacionada con una palabra.

Ejemplo:

HOLA -> images.id_palabra = ID de HOLA

## 8. Voz

En `/traductor` selecciona Voz.

Usa Chrome o Edge.

El navegador escucha en español de México (`es-MX`) y coloca el texto
dentro del formulario.

## 9. Cámara e IA

Abre:

http://127.0.0.1:8000/camara

Empieza con 5-10 clases.

Captura 100-200 muestras por seña.

## 10. Preparar Python

```bash
cd ia
python -m venv venv
venv\Scripts\activate
pip install -r requirements.txt
```

Copia:

`.env.example`

como:

`.env`

## 11. Entrenar

Con MySQL/XAMPP iniciado:

```bash
python entrenar.py
```

Se crearán:

- modelos/modelo_lsm.joblib
- modelos/encoder_lsm.joblib
- modelos/metadata.json

## 12. Ejecutar API IA

```bash
python api.py
```

Comprueba:

http://127.0.0.1:5001/health

## 13. Ejecutar ambos servidores

Terminal 1:

```bash
php artisan serve
```

Terminal 2:

```bash
cd ia
venv\Scripts\activate
python api.py
```

## 14. Flujo final

Texto:
Texto -> Laravel -> words -> images -> tarjetas LSM

Voz:
Micrófono -> navegador -> texto -> Laravel -> imágenes LSM

Cámara:
Webcam -> MediaPipe -> 63 landmarks -> Python IA -> palabra -> frase -> historial

## Nota importante sobre la IA

Los dibujos de LSM guardados en `images` sirven para mostrar la traducción.
La IA de cámara se entrena con muestras capturadas desde la webcam, porque
una mano real y un dibujo son visualmente muy diferentes.

La versión incluida clasifica posturas de mano estáticas. Para señas que
dependen de movimiento, una versión posterior debe capturar secuencias de
fotogramas y usar un modelo temporal (LSTM/GRU/Transformer).
