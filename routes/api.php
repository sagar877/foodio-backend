<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;

Route::get('/users', [UserController::class, 'index']);

Route::post('/register' , [AuthController::class , 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/login', [AuthController::class , 'login']);
});


