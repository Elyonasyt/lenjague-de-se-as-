<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // 🧩 REGISTRO DE USUARIO
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'correo' => 'required|email|unique:usuarios,correo',
            'contrasena' => 'required|min:6|confirmed',
        ]);

        DB::table('usuarios')->insert([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'contrasena' => Hash::make($request->contrasena),
            'fecha_registro' => now(),
        ]);

        return redirect('/inicio')->with('success', 'Registro exitoso. Inicia sesión.');
    }

    // 🧩 INICIO DE SESIÓN
    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'contrasena' => 'required',
        ]);

        $usuario = DB::table('usuarios')->where('correo', $request->correo)->first();

        if ($usuario && Hash::check($request->contrasena, $usuario->contrasena)) {
            // Guardar datos en sesión manualmente
            Session::put('usuario_id', $usuario->id);
            Session::put('usuario_nombre', $usuario->nombre);

            return redirect('/traductor');
        }

        return back()->withErrors(['correo' => 'Correo o contraseña incorrectos.']);
    }

    // 🧩 CIERRE DE SESIÓN
    public function logout()
    {
        Session::flush();
        return redirect('/inicio')->with('success', 'Sesión cerrada correctamente.');
    }
}

