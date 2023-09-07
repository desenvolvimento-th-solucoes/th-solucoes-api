<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

Route::post('/register', [UserController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);
Route::get('/check', [AuthController::class, 'check']);

Route::middleware(['auth:api'])->group(function(){
    // Route::get('/rota-protegida', 'Controlador@metodo')->middleware('auth:api');
});