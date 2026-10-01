<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class CamaraController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | MOSTRAR PÁGINA DE CÁMARA
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        /*
         * No usamos categories porque en tu base de datos
         * actualmente no existe categories.nombre.
         *
         * Solo obtenemos directamente las palabras.
         */

        $palabras = DB::table('words')
            ->select(
                'id_palabra',
                'palabra_espanol',
                DB::raw("'Palabra / Letra' AS tipo_categoria")
            )
            ->orderBy('palabra_espanol', 'asc')
            ->get();

        return view(
            'camara',
            compact('palabras')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GUARDAR MUESTRAS PARA ENTRENAMIENTO
    |--------------------------------------------------------------------------
    */

    public function guardarMuestras(Request $request)
    {
        $request->validate([
            'id_palabra' => 'required|integer',
            'muestras' => 'required|array|min:1',
            'muestras.*' => 'required|array|size:63',
            'muestras.*.*' => 'required|numeric',
        ]);


        /*
         * Buscar palabra seleccionada.
         */

        $palabra = DB::table('words')
            ->where(
                'id_palabra',
                $request->id_palabra
            )
            ->first();


        if (!$palabra) {

            return response()->json([
                'ok' => false,
                'message' => 'La palabra seleccionada no existe.'
            ], 404);
        }


        /*
         * Crear carpeta:
         *
         * storage/app/lsm_dataset
         */

        $directorio = storage_path(
            'app/lsm_dataset'
        );


        if (!File::exists($directorio)) {

            File::makeDirectory(
                $directorio,
                0755,
                true
            );
        }


        /*
         * Archivo donde se guardarán
         * todas las muestras.
         */

        $archivo = $directorio
            . DIRECTORY_SEPARATOR
            . 'muestras.jsonl';


        $contenido = "";


        foreach ($request->muestras as $muestra) {

            $registro = [

                'id_palabra' =>
                    $palabra->id_palabra,

                'etiqueta' =>
                    mb_strtolower(
                        trim(
                            $palabra->palabra_espanol
                        )
                    ),

                'puntos' =>
                    array_map(
                        'floatval',
                        $muestra
                    )

            ];


            $contenido .=
                json_encode(
                    $registro,
                    JSON_UNESCAPED_UNICODE
                )
                . PHP_EOL;
        }


        File::append(
            $archivo,
            $contenido
        );


        return response()->json([
            'ok' => true,
            'message' => 'Muestras guardadas correctamente.',
            'palabra' => $palabra->palabra_espanol,
            'cantidad' => count($request->muestras)
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | GUARDAR FRASE RECONOCIDA
    |--------------------------------------------------------------------------
    */

    public function guardarTraduccion(Request $request)
    {
        $request->validate([
            'frase' => 'required|string|max:2000'
        ]);


        $idUsuario = session(
            'usuario_id'
        );


        if (!$idUsuario) {

            return response()->json([
                'ok' => false,
                'message' => 'La sesión del usuario expiró.'
            ], 401);
        }


        /*
         * Datos básicos para translations.
         */

        $datos = [

            'texto_ingresado' =>
                trim($request->frase),

            'resultado_json' =>
                json_encode(
                    [
                        'origen' => 'CAMARA',
                        'texto' => trim($request->frase)
                    ],
                    JSON_UNESCAPED_UNICODE
                ),

            'fecha_traduccion' =>
                now(),

            'id_usuario' =>
                $idUsuario,

            'id_palabra' =>
                null
        ];


        /*
         * Si existe tipo_entrada,
         * guardamos CAMARA.
         */

        if (
            Schema::hasColumn(
                'translations',
                'tipo_entrada'
            )
        ) {

            $datos['tipo_entrada'] =
                'CAMARA';
        }


        /*
         * Insertar traducción.
         */

        $idTraduccion = DB::table(
            'translations'
        )
        ->insertGetId(
            $datos
        );


        /*
         * Guardar en historial.
         */

        DB::table('history')
            ->insert([
                'id_traduccion' =>
                    $idTraduccion
            ]);


        return response()->json([
            'ok' => true,
            'message' => 'Frase guardada correctamente.',
            'id_traduccion' => $idTraduccion
        ]);
    }
}