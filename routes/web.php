<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\TraductorController;
use App\Http\Controllers\PalabraController;
use App\Http\Controllers\BitacoraController;
/*
|--------------------------------------------------------------------------
| PAGINA PRINCIPAL
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});
/*
|--------------------------------------------------------------------------
| INICIO
|--------------------------------------------------------------------------
*/
Route::get('/inicio', function () {
    return view('inicio');
})->name('inicio');
/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'login'])
    ->name('login');
Route::post('/login', [LoginController::class, 'autenticar'])
    ->name('login.autenticar');
Route::get('/logout', [LoginController::class, 'logout'])
 ->name('logout');
/*
|--------------------------------------------------------------------------
| REGISTRO
|--------------------------------------------------------------------------
*/
Route::get('/registro', [RegistroController::class, 'mostrarForm'])
    ->name('registro.form');
Route::post('/registro', [RegistroController::class, 'guardarUsuario'])
    ->name('registro.guardar');
/*
|--------------------------------------------------------------------------
| TRADUCTOR
|--------------------------------------------------------------------------
*/
Route::get('/traductor', [TraductorController::class, 'index'])
    ->name('traductor.index');
Route::post('/traductor', [TraductorController::class, 'traducir'])
    ->name('traductor.traducir');
Route::get('/traductor/reconsultar/{id}', [TraductorController::class, 'reconsultar'])
    ->name('traductor.reconsultar');
/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::get('/admin', function () {
    $usuarios = DB::table('users')->get();
    $categorias = DB::table('categories')->get();
    $palabras = DB::table('words')->get();
    $traducciones = DB::table('translations')->get();
    return view('admin', compact(
        'usuarios',
        'categorias',
        'palabras',
        'traducciones'
    ));

})->name('admin');

/*
|--------------------------------------------------------------------------
| PALABRAS
|--------------------------------------------------------------------------
*/

Route::get('/palabras', [PalabraController::class, 'index'])
    ->name('palabras.index');

Route::get('/palabras/create', [PalabraController::class, 'create'])
    ->name('palabras.create');

Route::post('/palabras/store', [PalabraController::class, 'store'])
    ->name('palabras.store');

Route::get('/palabras/edit/{id}', [PalabraController::class, 'edit'])
    ->name('palabras.edit');

Route::post('/palabras/update/{id}', [PalabraController::class, 'update'])
    ->name('palabras.update');

Route::get('/palabras/delete/{id}', [PalabraController::class, 'destroy'])
    ->name('palabras.delete');

/*
|--------------------------------------------------------------------------
| BITACORA
|--------------------------------------------------------------------------
*/

Route::get('/bitacora', [BitacoraController::class, 'verBitacora'])
    ->name('bitacora');
    
    use App\Http\Controllers\AuditoriaController;

Route::get('/auditoria',[AuditoriaController::class,'index']);


Route::get(
    '/traductor',
    [TraductorController::class, 'index']
)->name('traductor.index');

Route::post(
    '/traductor',
    [TraductorController::class, 'traducir']
)->name('traductor.traducir');

Route::get(
    '/traductor/reconsultar/{id}',
    [TraductorController::class, 'reconsultar']
)->name('traductor.reconsultar');