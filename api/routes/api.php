<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\NegocioController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\HorarioNegocioController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\FavoritosController;

Route::post('register', [AuthController::class, 'register']);
Route::post('/register-negocio', [NegocioController::class, 'store']);
Route::get('/negocios', [NegocioController::class, 'index']);
Route::resource('negocios', NegocioController::class)->except(['index'])->middleware('firebase.auth');
Route::post('user', [AuthController::class,'getUserByUid'])->middleware('firebase.auth');
Route::resource('services', ServicesController::class)->middleware('firebase.auth');
Route::get('categorias', [CategoriasController::class, 'index']);
Route::resource('citas', CitaController::class)->middleware('firebase.auth');
Route::get('/users/{userId}/negocios', [NegocioController::class, 'getNegociosByUser'])->middleware('firebase.auth');
Route::resource('horarios-negocio', HorarioNegocioController::class)->middleware('firebase.auth');
Route::get('/negocios/{id}/huecos-disponibles', [HorarioNegocioController::class, 'huecosDisponibles'])->middleware('firebase.auth');
Route::patch('/users/{userId}', [AuthController::class, 'updateUser'])->middleware('firebase.auth');
Route::get('/negocios/{negocio}/comentarios', [ComentarioController::class, 'index']);
Route::post('/negocios/{negocio}/comentarios', [ComentarioController::class, 'store'])->middleware('firebase.auth');
Route::get('/citas/negocio/{negocioId}/usuario/{userId}', [CitaController::class, 'getByNegocioAndUsuario']);
Route::post('/negocios/{negocio}/imagen', [NegocioController::class, 'updateImage'])->middleware('firebase.auth');
Route::get('/favoritos/{userId}', [FavoritosController::class, 'index'])->middleware('firebase.auth');
Route::get('/negocios/favoritos/{userId}', [NegocioController::class, 'getFavoritos'])->middleware('firebase.auth');
Route::post('/favoritos/toggle', [FavoritosController::class, 'toggle'])->middleware('firebase.auth');
