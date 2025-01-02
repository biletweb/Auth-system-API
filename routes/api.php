<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Users\SearchController;
use App\Http\Controllers\Admin\Users\UsersController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/profile', [ProfileController::class, 'index'])->middleware('auth:sanctum');
Route::post('/profile/settings/change-password', [ProfileController::class, 'changePassword'])->middleware('auth:sanctum');
Route::post('/profile/settings/update-personal-info', [ProfileController::class, 'updatePersonalInfo'])->middleware('auth:sanctum');
Route::post('/profile/settings/delete-account', [ProfileController::class, 'deleteAccount'])->middleware('auth:sanctum');
Route::post('/profile/settings/confirm-email', [AuthController::class, 'confirmEmail'])->middleware(['auth:sanctum'])->name('verification.verify');
Route::post('/profile/settings/resend-email', [AuthController::class, 'resendEmail'])->middleware(['auth:sanctum'])->name('verification.send');
Route::post('/profile/settings/change-locale', [ProfileController::class, 'changeLocale'])->middleware(['auth:sanctum']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/admin/users', [UsersController::class, 'index'])->middleware(['auth:sanctum', 'admin']);
Route::get('/admin/users/search', [SearchController::class, 'userSearch'])->middleware(['auth:sanctum', 'admin']);
Route::get('/admin/users/sort-by', [SearchController::class, 'sortBy'])->middleware(['auth:sanctum', 'admin']);
Route::post('/admin/users/change/role', [UsersController::class, 'changeRole'])->middleware(['auth:sanctum', 'admin']);
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->middleware(['auth:sanctum', 'admin']);
