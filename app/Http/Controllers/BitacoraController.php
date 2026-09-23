<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class BitacoraController extends Controller
{
    public function index()
    {
        $this->soloAdmin();

        $bitacora = DB::table('bitacora as b')
            ->leftJoin('users as u', 'u.id_user', '=', 'b.id_usuario')
            ->select(
                'b.*',
                'u.first_name',
                'u.last_name'
            )
            ->orderByDesc('b.fecha')
            ->limit(500)
            ->get();

        return view('bitacora', compact('bitacora'));
    }

    public function laravelLogs()
    {
        $this->soloAdmin();

        $path = storage_path('logs/laravel.log');

        $logs = [];

        if (File::exists($path)) {
            $contenido = File::get($path);
            $lineas = preg_split('/\r\n|\r|\n/', $contenido);
            $logs = array_slice(array_reverse($lineas), 0, 250);
        }

        return view('log', compact('logs'));
    }

    private function soloAdmin(): void
    {
        abort_if(!session('usuario_id'), 401);
        abort_if(session('usuario_role') !== 'ADMIN', 403);
    }
}
