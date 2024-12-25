<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/profile', [ProfileController::class, 'index'])->middleware('auth:sanctum');
Route::post('/profile/settings/change-password', [ProfileController::class, 'changePassword'])->middleware('auth:sanctum');
Route::post('/profile/settings/update-personal-info', [ProfileController::class, 'updatePersonalInfo'])->middleware('auth:sanctum');
Route::post('/profile/settings/delete-account', [ProfileController::class, 'deleteAccount'])->middleware('auth:sanctum');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register'])->name('verification.verify');

Route::post('/profile/settings/confirm-email', function (Request $request) {
    if ($request->user()->hasVerifiedEmail()) {
        return response()->json(['warning' => 'You have already verified your email.']);
    }
    if ($request->securityCode !== $request->user()->security_code) {
        return response()->json(['error' => 'Invalid security code.']);
    }

    $request->user()->markEmailAsVerified();
    $request->user()->update([
        'security_code' => null,
    ]);

    return response()->json(['message' => 'You have successfully verified your email.']);
})->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.verify');
