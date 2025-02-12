<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
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


Route::controller(CommentController::class)->group(function () {
        Route::post('/add-comment','add_comment')->middleware('auth:sanctum');
        Route::get('/get-comments/{id}', 'get_comments');
    }
);
