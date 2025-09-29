<?php

use App\Http\Controllers\PizarraController;
use App\Http\Controllers\ReunionController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {    
    return view('diagramas.usuario.login');    
})->middleware('guest');

Route::get('/register', [UsuarioController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [UsuarioController::class, 'register']);
Route::get('/login', [ UsuarioController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UsuarioController::class, 'login']);
Route::post('/logout', [UsuarioController::class, 'logout'])->name('logout');

Route::get('/reunion',[ReunionController::class,'index'])->name('reunion');

Route::get('/pizarra',[ReunionController::class,'create'])->name('reunion.create');
Route::post('/pizarra',[ReunionController::class,'join'])->name('reunion.join');
Route::get('/finalizar',[ReunionController::class,'finalizar'])->name('reunion.finalizar');

Route::get('/pizarra/{link}',[PizarraController::class,'index'])->name('pizarra');
Route::post('/pizarra-guardar',[PizarraController::class,'guardar']);

Route::post('/cargarImagen', [PizarraController::class, 'cargarImagen']);
Route::post('/addTexto', [PizarraController::class, 'addTexto']);
Route::post('/generar-backend', [PizarraController::class, 'generarBackend']);
//colaboracion
Route::post('/colaboracion-add-clase', [PizarraController::class, 'colaboracionAddClase']);
Route::post('/colaboracion-clase-movido', [PizarraController::class, 'colaboracionClaseMovido']);
Route::post('/colaboracion-add-relacion', [PizarraController::class, 'colaboracionAddRelacion']);
Route::post('/colaboracion-relacion-imagen', [PizarraController::class, 'colaboracionRelacionImagen']);

Route::post('/colaboracion-guardar-cambios', [PizarraController::class, 'colaboracionGuardarCambios']);
Route::post('/colaboracion-clases-borradas', [PizarraController::class, 'colaboracionClasesBorradas']);

