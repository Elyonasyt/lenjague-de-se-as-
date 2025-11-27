<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/inicio', function () {
    return view('inicio');
});

Route::get('/registro', function () {
    return view('registro');
});

Route::get('/traductor', function () {
    return view('traductor');
});
Route::get('/traductor_gratis', function () {
    return view('traductor_gratis');
});
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\LoginController;

// Login
Route::get('/inicio', [LoginController::class, 'mostrarFormulario']);
Route::post('/inicio', [LoginController::class, 'autenticar'])->name('login');
Route::get('/logout', [LoginController::class, 'cerrarSesion']);

// Registro
Route::get('/registro', [RegistroController::class, 'mostrarFormulario']);
Route::post('/registro', [RegistroController::class, 'registrar'])->name('registrar');

// Otras vistas
Route::get('/', function () { return view('welcome'); });
Route::get('/traductor', function () { return view('traductor'); });
Route::get('/traductor_gratis', function () { return view('traductor_gratis'); });


Route::get('/registro', [RegistroController::class, 'mostrarForm'])->name('registro.form');
Route::post('/registro', [RegistroController::class, 'guardarUsuario'])->name('registro.post');

// Inicio de sesión
Route::get('/inicio', [LoginController::class, 'mostrarFormulario']);
Route::post('/inicio', [LoginController::class, 'login'])->name('login.post');

Route::get('/inicio', function () {
    return view('inicio');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/registro', function () {
    return view('registro');
});

Route::post('/registro', [AuthController::class, 'register'])->name('registro.post');

Route::get('/traductor', function () {
    return view('traductor');
})->middleware('auth');

Route::get('/traductor_gratis', function () {
    return view('traductor_gratis');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/login', function () {
    return view('login'); // tu vista HTML
});

Route::post('/login', [LoginController::class, 'autenticar'])->name('login.autenticar');

Route::get('/traductor', function () {
    return view('traductor');
})->name('traductor');


use App\Http\Controllers\TraductorController;

Route::get('/traductor', [TraductorController::class, 'index'])->name('traductor');
Route::post('/traductor', [TraductorController::class, 'traducir'])->name('traductor.traducir');


Route::get('/lenguaje-senas', function () {
    return view('sign_language');
});

Route::get('/traductor', function () {
    return view('traductor');
})->name('traductor.view');

Route::post('/traducir-senia', [TraductorController::class, 'traducirSenia'])->name('traductor.senia');

Route::get('/traductor', [TraductorController::class, 'index'])->name('traductor.index');
Route::post('/traducir', [TraductorController::class, 'traducir'])->name('traductor.traducir');
Route::post('/traducir-senia', [TraductorController::class, 'traducirSenia'])->name('traductor.senia');


Route::get('/traductor', [TraductorController::class, 'index'])->name('traductor.index');
Route::post('/traductor', [TraductorController::class, 'traducir'])->name('traductor.traducir');


Route::get('/', function () {
    return view('login');
});

// rutas de login
Route::post('/login', [LoginController::class, 'autenticar'])->name('login.autenticar');

// rutas del traductor
Route::get('/traductor', [TraductorController::class, 'index'])->name('traductor.index');
Route::post('/traductor', [TraductorController::class, 'traducir'])->name('traductor.traducir');
