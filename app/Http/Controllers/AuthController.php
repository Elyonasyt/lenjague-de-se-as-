<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{

    // ==============================
    // REGISTRO
    // ==============================
    public function register(Request $request)
    {

        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'middle_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed'
        ]);

        DB::table('users')->insert([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_name' => $request->middle_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'registration_date' => now()
        ]);

        return redirect('/inicio')->with('success','Registro exitoso. Inicia sesión.');
    }


    // ==============================
    // LOGIN
    // ==============================
    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $usuario = DB::table('users')
            ->where('email',$request->email)
            ->first();

        if($usuario && Hash::check($request->password,$usuario->Passwor)){

            Session::put('usuario_id',$usuario->id_user);
            Session::put('usuario_nombre',$usuario->first_name);

            return redirect('/traductor');

        }

        return back()->withErrors([
            'credenciales' => 'Correo o contraseña incorrectos'
        ]);

    }


    // ==============================
    // LOGOUT
    // ==============================
    public function logout()
    {

        Session::flush();

        return redirect('/inicio')->with('success','Sesión cerrada correctamente');

    }

}
