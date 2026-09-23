<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginForm()
    {
        if (session()->has('usuario_id')) {
            return redirect()->route('traductor');
        }

        return view('inicio');
    }

    public function registroForm()
    {
        if (session()->has('usuario_id')) {
            return redirect()->route('traductor');
        }

        return view('registro');
    }

    public function registro(Request $request)
    {
        $request->validate([
            'first_name' => ['required','string','max:100'],
            'last_name' => ['required','string','max:100'],
            'middle_name' => ['nullable','string','max:100'],
            'email' => ['required','email','max:150','unique:users,email'],
            'password' => ['required','string','min:8','confirmed'],
        ], [
            'email.unique' => 'Ese correo ya está registrado.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        DB::table('users')->insert([
            'first_name' => trim($request->first_name),
            'last_name' => trim($request->last_name),
            'middle_name' => $request->middle_name ? trim($request->middle_name) : null,
            'email' => strtolower(trim($request->email)),
            'password' => Hash::make($request->password),
            'registration_date' => now()->toDateString(),
            'role' => 'USER',
        ]);

        return redirect()->route('login')
            ->with('success', 'Cuenta creada correctamente. Ya puedes iniciar sesión.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required','email'],
            'password' => ['required','string'],
        ]);

        $usuario = DB::table('users')
            ->where('email', strtolower(trim($request->email)))
            ->first();

        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return back()
                ->withErrors(['email' => 'Correo o contraseña incorrectos.'])
                ->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        session([
            'usuario_id' => $usuario->id_user,
            'usuario_nombre' => $usuario->first_name . ' ' . $usuario->last_name,
            'usuario_email' => $usuario->email,
            'usuario_role' => $usuario->role,
        ]);

        DB::table('bitacora')->insert([
            'id_usuario' => $usuario->id_user,
            'accion' => 'LOGIN',
            'tabla' => 'users',
            'descripcion' => 'Inicio de sesión',
            'fecha' => now(),
        ]);

        if ($usuario->role === 'ADMIN') {
            return redirect()->route('admin');
        }

        return redirect()->route('traductor');
    }

    public function logout(Request $request)
    {
        if (session()->has('usuario_id')) {
            DB::table('bitacora')->insert([
                'id_usuario' => session('usuario_id'),
                'accion' => 'LOGOUT',
                'tabla' => 'users',
                'descripcion' => 'Cierre de sesión',
                'fecha' => now(),
            ]);
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
