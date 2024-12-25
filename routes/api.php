<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/profile', [ProfileController::class, 'index'])->middleware('auth:sanctum');
Route::post('/profile/settings/change-password', [ProfileController::class, 'changePassword'])->middleware('auth:sanctum');
Route::post('/profile/settings/update-personal-info', [ProfileController::class, 'updatePersonalInfo'])->middleware('auth:sanctum');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
