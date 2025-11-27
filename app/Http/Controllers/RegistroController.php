<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RegistroController extends Controller
{
    // Mostrar el formulario
    public function mostrarForm()
    {
        return view('registro');
    }

    // Guardar usuario en la base de datos
    public function guardarUsuario(Request $request)
    {
        // Validar datos
        $request->validate([
            'nombre' => 'required|string|max:100',
            'correo' => 'required|email|unique:usuarios,correo',
            'contrasena' => 'required|string|min:6|confirmed',
        ]);

        // Guardar en la tabla usuarios
        DB::table('usuarios')->insert([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'contrasena' => Hash::make($request->contrasena),
        ]);

        return redirect('/inicio')->with('success', 'Usuario registrado correctamente');
    }
}
