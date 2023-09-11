<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::post('/login', [AuthenticationController::class, 'login']);
Route::get('/destroy', [AuthenticationController::class, 'destroy']);
Route::get('/check', [AuthenticationController::class, 'check']);

Route::post('/register', [UserController::class, 'register']);

Route::middleware(['auth:api'])->group(function(){
    // Route::get('/rota-protegida', 'Controlador@metodo')->middleware('auth:api');
});