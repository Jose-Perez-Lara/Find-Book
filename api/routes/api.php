<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\NegocioController;

Route::post('register', [AuthController::class, 'register']);
Route::post('/register-negocio', [NegocioController::class, 'create']);
Route::resource('negocios', NegocioController::class)->middleware('firebase.auth');
Route::post('user', [AuthController::class,'getUserByUid'])->middleware('firebase.auth');