<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\RazorpayController;


Route::post('/register' , [AuthController::class , 'register']);
Route::post('/login', [AuthController::class , 'login']);
Route::get('/check-authenticated', [AuthController::class, 'checkAuthenticated']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/sync-cart', [CartController::class, 'sync']);
    Route::post('/add-to-cart', [CartController::class, 'addToCart']);
    Route::post('/logout', [AuthController::class, 'destroy']);
    Route::post('/create-order', [RazorpayController::class, 'createOrder']);
});
