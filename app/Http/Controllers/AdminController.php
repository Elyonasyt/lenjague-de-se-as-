<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{

    public function index(){

        $usuarios = DB::table('users')->get();

        $categorias = DB::table('categories')->get();

        $palabras = DB::table('words')->get();

        $palabras = DB::table('words as w')
            ->join('categories as c','w.id_categoria','=','c.id_categoria')
            ->select(
                'w.id_palabra',
                'w.palabra_espanol',
                'w.id_categoria',
                'c.nombre_categoria'
            )
            ->get();

        $traducciones = DB::table('translations')
            ->join('users','translations.id_usuario','=','users.id_user')
            ->select('translations.*','users.first_name','users.last_name')
            ->get();

        return view('admin',compact(
            'usuarios',
            'categorias',
            'palabras',
            'traducciones'
        ));

    }

}
