<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;


Route::post('/register' , [AuthController::class , 'register']);
Route::post('/login', [AuthController::class , 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user', [UserController::class, 'index']);
    Route::post('/sync-cart', [CartController::class, 'sync']);
});
