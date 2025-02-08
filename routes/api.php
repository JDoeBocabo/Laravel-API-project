<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/user/{id}', [AuthController::class, 'user']);
Route::apiResource('posts', PostController::class);

Route::get('/all-users', [AuthController::class, 'users'])->middleware('auth:sanctum');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/addComment', [PostController::class, 'addComment'])->middleware('auth:sanctum');
