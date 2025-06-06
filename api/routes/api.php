<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\NegocioController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\CitasController;
use App\Http\Controllers\HorarioNegocioController;

Route::post('register', [AuthController::class, 'register']);
Route::post('/register-negocio', [NegocioController::class, 'create']);
Route::resource('negocios', NegocioController::class)->middleware('firebase.auth');
Route::post('user', [AuthController::class,'getUserByUid'])->middleware('firebase.auth');
Route::resource('services', ServicesController::class)->middleware('firebase.auth');
Route::get('categorias', [CategoriasController::class, 'index']);
Route::resource('citas', CitasController::class)->middleware('firebase.auth');
Route::get('/users/{userId}/negocios', [NegocioController::class, 'getNegociosByUser'])->middleware('firebase.auth');
Route::resource('horarios-negocio', HorarioNegocioController::class)->middleware('firebase.auth');
Route::get('/negocios/{id}/huecos-disponibles', [HorarioNegocioController::class, 'huecosDisponibles'])->middleware('firebase.auth');
