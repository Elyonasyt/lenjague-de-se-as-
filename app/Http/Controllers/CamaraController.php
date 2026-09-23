<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CamaraController extends Controller
{
    public function index()
    {
        $this->usuarioId();

        $palabras = DB::table('words as w')
            ->join('categories as c', 'c.id_categoria', '=', 'w.id_categoria')
            ->select(
                'w.id_palabra',
                'w.palabra_espanol',
                'c.tipo_categoria',
                'c.nombre_categoria'
            )
            ->orderBy('c.tipo_categoria')
            ->orderBy('w.palabra_espanol')
            ->get();

        return view('camara', compact('palabras'));
    }

    public function guardarMuestras(Request $request)
    {
        $this->usuarioId();

        $request->validate([
            'id_palabra' => ['required','integer','exists:words,id_palabra'],
            'muestras' => ['required','array','min:1','max:250'],
            'muestras.*' => ['required','array','size:63'],
            'muestras.*.*' => ['required','numeric'],
        ]);

        $filas = [];

        foreach ($request->muestras as $landmarks) {
            $filas[] = [
                'id_palabra' => (int)$request->id_palabra,
                'landmarks_json' => json_encode(
                    array_map('floatval', $landmarks),
                    JSON_UNESCAPED_UNICODE
                ),
                'fecha_registro' => now(),
            ];
        }

        DB::table('sign_samples')->insert($filas);

        return response()->json([
            'ok' => true,
            'guardadas' => count($filas),
        ]);
    }

    public function guardarTraduccion(Request $request)
    {
        $idUsuario = $this->usuarioId();

        $request->validate([
            'texto' => ['required','string','max:1000'],
            'confianza' => ['nullable','numeric','between:0,1'],
        ]);

        $id = DB::table('translations')->insertGetId([
            'texto_ingresado' => trim($request->texto),
            'fecha_traduccion' => now(),
            'id_usuario' => $idUsuario,
            'id_palabra' => null,
            'resultado_json' => json_encode([
                'confianza' => $request->confianza,
                'origen' => 'IA_CAMARA',
            ], JSON_UNESCAPED_UNICODE),
            'tipo_entrada' => 'CAMARA',
        ]);

        DB::table('history')->insert([
            'id_traduccion' => $id,
        ]);

        return response()->json([
            'ok' => true,
            'id_traduccion' => $id,
        ]);
    }

    private function usuarioId(): int
    {
        $id = session('usuario_id');
        abort_if(!$id, 401, 'Debes iniciar sesión.');
        return (int)$id;
    }
}
