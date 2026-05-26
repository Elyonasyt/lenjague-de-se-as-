<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TraductorController extends Controller
{

    /* =========================================
       INDEX
    ========================================= */

    public function index()
    {
        $userId = Session::get('usuario_id');

        /* =========================
           OBTENER USUARIO
        ========================= */

        $usuario = DB::table('users')
            ->where('id_user', $userId)
            ->first();

        /* =========================
           HISTORIAL
        ========================= */

        $historial = DB::table('translations')
            ->where('id_usuario', $userId)
            ->orderBy('fecha_traduccion', 'desc')
            ->get();

        /* =========================
           RESULTADO
        ========================= */

        $resultado = Session::get('resultado', []);

        return view('traductor', [
            'usuario' => $usuario,
            'historial' => $historial,
            'resultado' => $resultado,
            'textoAnterior' => session('textoAnterior', '')
        ]);
    }

    /* =========================================
       TRADUCIR
    ========================================= */

    public function traducir(Request $request)
    {
        $request->validate([
            'texto' => 'required|string|max:1000'
        ]);

        $textoCompleto = trim($request->texto);

        // Convertir a minúsculas
        $texto = mb_strtolower($textoCompleto, 'UTF-8');

        $userId = Session::get('usuario_id');

        /* =========================
           SEPARAR PALABRAS
        ========================= */

        $palabras = preg_split(
            '/\s+/',
            $texto,
            -1,
            PREG_SPLIT_NO_EMPTY
        );

        $resultado = [];

        foreach ($palabras as $palabra) {

            /* =========================
               BUSCAR PALABRA COMPLETA
            ========================= */

            $imagen = DB::table('images')
                ->whereRaw(
                    'LOWER(descripcion) = ?',
                    [$palabra]
                )
                ->first();

            if ($imagen) {

                $resultado[] = (object)[
                    'texto' => ucfirst($palabra),
                    'ruta_imagen' => $imagen->ruta_imagen
                ];

            } else {

                /* =========================
                   DELETREAR LETRA POR LETRA
                ========================= */

                $letras = preg_split(
                    '//u',
                    $palabra,
                    -1,
                    PREG_SPLIT_NO_EMPTY
                );

                foreach ($letras as $letra) {

                    $imagenLetra = DB::table('images')
                        ->whereRaw(
                            'LOWER(descripcion) = ?',
                            [mb_strtolower($letra, 'UTF-8')]
                        )
                        ->first();

                    $resultado[] = (object)[
                        'texto' => strtoupper($letra),
                        'ruta_imagen' => $imagenLetra
                            ? $imagenLetra->ruta_imagen
                            : 'images/signs/no-image.png'
                    ];
                }
            }
        }

        /* =========================
           GUARDAR HISTORIAL
        ========================= */

        DB::table('translations')->insert([
            'texto_ingresado' => $textoCompleto,
            'resultado_json' => json_encode($resultado),
            'fecha_traduccion' => now(),
            'id_usuario' => $userId
        ]);

        /* =========================
           GUARDAR SESSION
        ========================= */

        Session::put('resultado', $resultado);

        return redirect()
            ->route('traductor.index')
            ->with('textoAnterior', $textoCompleto);
    }

    /* =========================================
       RECONSULTAR
    ========================================= */

    public function reconsultar($id)
    {
        $userId = Session::get('usuario_id');

        /* =========================
           USUARIO
        ========================= */

        $usuario = DB::table('users')
            ->where('id_user', $userId)
            ->first();

        /* =========================
           HISTORIAL
        ========================= */

        $historial = DB::table('translations')
            ->where('id_usuario', $userId)
            ->orderBy('fecha_traduccion', 'desc')
            ->get();

        /* =========================
           OBTENER REGISTRO
        ========================= */

        $registro = DB::table('translations')
            ->where('id_traduccion', $id)
            ->first();

        if (!$registro) {

            return redirect()
                ->route('traductor.index');
        }

        $resultado = json_decode($registro->resultado_json);

        return view('traductor', [
            'usuario' => $usuario,
            'historial' => $historial,
            'resultado' => $resultado,
            'textoAnterior' => $registro->texto_ingresado
        ]);
    }
}