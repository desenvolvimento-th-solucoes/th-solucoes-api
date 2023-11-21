<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\DeviceController;

Route::middleware(['middleware' => 'jwt.api'])->group(function() {
    Route::get('/check', [AuthController::class, 'checkToken']);
    Route::get('/destroy', [AuthController::class, 'destroyToken']);
    Route::put('/save', [UserController::class, 'save']);
    Route::put('/redefine', [UserController::class, 'redefine']);
});

Route::middleware(['middleware' => 'jwt.api'])->group(function() {
});

Route::get('/administrator/device/all', [DeviceController::class, "recoverAllDevices"]);
Route::get('/administrator/user/all', [UserController::class, "recoverAllUsers"]);
Route::post('/administrator/device/assign', [DeviceController::class, "assign"]);
Route::post('/administrator/device/showPassword', [DeviceController::class, "showPassword"]);

Route::middleware(['throttle:5,1'])->group(function() {
    Route::post('/contact', [MailController::class, 'contact']);
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});