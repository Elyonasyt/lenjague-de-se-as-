<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{

    // Mostrar login
    public function login()
    {
        return view('inicio');
    }


    // Autenticar usuario
    public function autenticar(Request $request)
    {

        // Validación
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);


        // Buscar usuario
        $usuario = DB::table('users')
            ->where('email', trim($request->email))
            ->first();


        // Verificar contraseña
        if ($usuario && Hash::check($request->password, $usuario->password)) {

            Session::put('usuario_id', $usuario->id_user);
            Session::put('usuario_nombre', $usuario->first_name);

            return redirect()->route('traductor.index');

        }


        // Error de login
        return back()
            ->withErrors(['error' => 'Correo o contraseña incorrectos'])
            ->withInput();

    }


    // Cerrar sesión
    public function logout()
    {

        Session::flush();

        return redirect()->route('login');

    }

}
