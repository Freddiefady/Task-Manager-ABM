<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Task\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Authentication Routes
Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);
Route::delete('auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
// Tasks Routes
Route::apiResource('tasks', TaskController::class)->middleware('auth:sanctum');
