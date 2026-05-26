<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class AuditoriaController extends Controller
{
    public function index()
    {
        $logs = DB::table('auditoria')
            ->orderBy('fecha', 'desc')
            ->limit(200)
            ->get();

        return view('auditoria.index', compact('logs'));
    }
}