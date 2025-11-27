<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TraductorController extends Controller
{
    public function index()
    {
        return view('traductor');
    }

    public function traducir(Request $request)
    {
        $request->validate([
            'palabra' => 'required|string'
        ]);

        $palabra = strtolower(trim($request->palabra));

        $diccionario = [
            'hola' => 'hola.png',
            'adios' => 'adios.png',
            'amor' => 'amor.png',
            'gracias' => 'gracias.png',
            'si' => 'si.png',
            'no' => 'no.png'
        ];

        $imagen = isset($diccionario[$palabra])
            ? asset('images/signs/' . $diccionario[$palabra])
            : asset('images/signs/desconocido.png');

        return view('traductor', [
            'palabra' => $palabra,
            'imagen' => $imagen
        ]);
    }
}
