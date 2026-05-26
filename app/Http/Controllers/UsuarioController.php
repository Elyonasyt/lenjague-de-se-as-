<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Auditoria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UsuarioController extends Controller
{
    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $nombreAnterior = $usuario->name;

        $usuario->update([
            'name' => $request->name,
            'email' => $request->email
        ]);

        // GUARDAR EN MYSQL
        Auditoria::create([
            'usuario' => Auth::user()->name,
            'accion' => 'UPDATE',
            'tabla_afectada' => 'users',
            'descripcion' =>
                'Cambió nombre de '.$nombreAnterior.
                ' a '.$request->name
        ]);

        // GUARDAR EN LOG LARAVEL
        Log::info(
            'Usuario '.Auth::user()->name.
            ' actualizó el usuario ID '.$id.
            ' Nombre: '.$nombreAnterior.
            ' -> '.$request->name
        );

        return redirect()->back();
    }
}