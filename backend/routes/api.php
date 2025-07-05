<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::post('/user', [UserController::class, 'store']);
Route::get('/user/{id}', [UserController::class, 'show']);
Route::get('/users', [UserController::class, 'index']);