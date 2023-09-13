<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/check', [AuthController::class, 'checkToken']);

Route::middleware(['middleware' => 'jwt.api'])->group(function(){
    Route::get('/destroy', [AuthController::class, 'destroyToken']);
    Route::put('/save', [UserController::class, 'save']);
});