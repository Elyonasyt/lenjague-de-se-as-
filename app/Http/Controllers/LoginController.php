<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function autenticar(Request $request)
    {
        $usuario = DB::table('usuarios')
            ->where('correo', $request->correo)
            ->first();

        if ($usuario && Hash::check($request->contrasena, $usuario->contrasena)) {
            // Guarda sesión y redirige
            session(['usuario_id' => $usuario->id]);
            return redirect()->route('traductor.index');

        } else {
            // Error de credenciales
            return back()->withErrors(['credenciales' => 'Correo o contraseña incorrectos.']);
        }
    }
}
