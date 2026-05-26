<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegistroController extends Controller
{

    // MOSTRAR FORMULARIO
    public function mostrarForm()
    {
        return view('registro');
    }


    // GUARDAR USUARIO
    public function guardarUsuario(Request $request)
    {

        // VALIDACIÓN
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'middle_name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email',
            'password' => 'required|confirmed|min:6'
        ]);


        // INSERTAR USUARIO
        DB::table('users')->insert([

            'first_name' => trim($request->first_name),
            'last_name' => trim($request->last_name),
            'middle_name' => trim($request->middle_name),
            'email' => trim($request->email),
            'password' => Hash::make($request->password),
            'registration_date' => now()

        ]);


        // REDIRECCIÓN
        return redirect()->route('login')
            ->with('success','Usuario registrado correctamente');

    }

}
