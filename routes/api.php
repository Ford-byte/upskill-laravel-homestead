<?php

use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/users', [UserController::class, 'getUsers']);

Route::post('/login', [UserController::class, 'login']);

Route::post('/register', [UserController::class, 'create']);

Route::middleware('auth:sanctum')->patch('/user', [UserController::class, 'editUser']);