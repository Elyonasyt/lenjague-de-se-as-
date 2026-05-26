<?php

namespace App\Http\Controllers;

class LogController extends Controller
{

    public function index()
    {

        $ruta = storage_path('logs/laravel.log');

        if(!file_exists($ruta)){

            $logs = [];

        }else{

            $contenido = file_get_contents($ruta);

            $logs = array_reverse(
                explode('[',$contenido)
            );

            $logs = array_filter($logs);

        }

        return view('log', compact('logs'));
    }

}