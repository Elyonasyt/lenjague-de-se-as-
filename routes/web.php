<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TraductorController;
use App\Http\Controllers\CamaraController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BitacoraController;

Route::get('/', fn() => view('welcome'))->name('welcome');

/* =========================
   AUTENTICACIÓN
========================= */
Route::get('/inicio', [AuthController::class, 'loginForm'])->name('login');
Route::post('/inicio', [AuthController::class, 'login'])->name('login.autenticar');

Route::get('/registro', [AuthController::class, 'registroForm'])->name('registro.form');
Route::post('/registro', [AuthController::class, 'registro'])->name('registro.guardar');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

/* =========================
   TRADUCTOR
========================= */
Route::get('/traductor', [TraductorController::class, 'index'])->name('traductor');
Route::post('/traductor/traducir', [TraductorController::class, 'traducir'])->name('traductor.traducir');
Route::get('/traductor/reconsultar/{id}', [TraductorController::class, 'reconsultar'])->name('traductor.reconsultar');

/* =========================
   CÁMARA + IA
========================= */
Route::get('/camara', [CamaraController::class, 'index'])->name('camara');
Route::post('/camara/muestras', [CamaraController::class, 'guardarMuestras'])->name('camara.muestras');
Route::post('/camara/guardar-traduccion', [CamaraController::class, 'guardarTraduccion'])->name('camara.guardarTraduccion');

/* =========================
   ADMINISTRADOR
========================= */
Route::get('/admin', [AdminController::class, 'index'])->name('admin');

Route::post('/usuarios/store', [AdminController::class, 'usuarioStore'])->name('usuarios.store');
Route::post('/usuarios/update/{id}', [AdminController::class, 'usuarioUpdate'])->name('usuarios.update');
Route::get('/usuarios/delete/{id}', [AdminController::class, 'usuarioDelete'])->name('usuarios.delete');

Route::post('/categorias/store', [AdminController::class, 'categoriaStore'])->name('categorias.store');
Route::post('/categorias/update/{id}', [AdminController::class, 'categoriaUpdate'])->name('categorias.update');
Route::get('/categorias/delete/{id}', [AdminController::class, 'categoriaDelete'])->name('categorias.delete');

Route::post('/palabras/store', [AdminController::class, 'palabraStore'])->name('palabras.store');
Route::post('/palabras/update/{id}', [AdminController::class, 'palabraUpdate'])->name('palabras.update');
Route::get('/palabras/delete/{id}', [AdminController::class, 'palabraDelete'])->name('palabras.delete');

Route::post('/imagenes/store', [AdminController::class, 'imagenStore'])->name('imagenes.store');
Route::post('/imagenes/update/{id}', [AdminController::class, 'imagenUpdate'])->name('imagenes.update');
Route::get('/imagenes/delete/{id}', [AdminController::class, 'imagenDelete'])->name('imagenes.delete');

Route::post('/traducciones/update/{id}', [AdminController::class, 'traduccionUpdate'])->name('traducciones.update');
Route::get('/traducciones/delete/{id}', [AdminController::class, 'traduccionDelete'])->name('traducciones.delete');

/* =========================
   BITÁCORA / LOGS
========================= */
Route::get('/bitacora', [BitacoraController::class, 'index'])->name('bitacora');
Route::get('/logs', [BitacoraController::class, 'laravelLogs'])->name('logs');
