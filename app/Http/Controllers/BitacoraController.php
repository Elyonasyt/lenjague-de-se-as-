<?php

    namespace App\Http\Controllers;

    use Illuminate\Support\Facades\DB;

    class BitacoraController extends Controller
    {
        public function verBitacora()
        {
            $bitacora = DB::table('bitacora')
                ->leftJoin('users', 'bitacora.usuario_id', '=', 'users.id_user')
                ->select(
                    'bitacora.*',
                    DB::raw("
                COALESCE(users.first_name, 'Usuario') as first_name,
                COALESCE(users.last_name, 'Desconocido') as last_name,
                COALESCE(users.middle_name, '') as middle_name
            ")
                )
                ->orderBy('fecha','desc')
                ->get();

            return view('bitacora', compact('bitacora'));
        }
    }
