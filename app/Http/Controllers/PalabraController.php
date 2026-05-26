<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PalabraController extends Controller
{

    public function index(){

        $palabras = DB::table('words')->get();

        return view('palabras.index',compact('palabras'));

    }



    public function create(){

        return view('palabras.create');

    }



    public function store(Request $request){

        DB::table('words')->insert([

            'palabra_espanol'=>$request->palabra,
            'id_categoria'=>$request->categoria

        ]);

        return redirect()->route('palabras.index');

    }



    public function edit($id){

        $palabra = DB::table('words')->where('id_palabra',$id)->first();

        return view('palabras.edit',compact('palabra'));

    }



    public function update(Request $request,$id){

        DB::table('words')
            ->where('id_palabra',$id)
            ->update([

                'palabra_espanol'=>$request->palabra,
                'id_categoria'=>$request->categoria

            ]);

        return redirect()->route('palabras.index');

    }



    public function destroy($id){

        DB::table('words')
            ->where('id_palabra',$id)
            ->delete();

        return redirect()->route('palabras.index');

    }

}
