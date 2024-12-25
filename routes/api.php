<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\ProfileController;

Route::get('/profile', [ProfileController::class, 'index'])->middleware('auth:sanctum');
Route::post('/profile/settings/change-password', [ProfileController::class, 'changePassword'])->middleware('auth:sanctum');
Route::post('/profile/settings/update-personal-info', [ProfileController::class, 'updatePersonalInfo'])->middleware('auth:sanctum');
Route::post('/profile/settings/delete-account', [ProfileController::class, 'deleteAccount'])->middleware('auth:sanctum');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


Route::get('/email/verify/{id}/{hash}', function (Request $request) {
    $user = User::find($request->route('id'));
    if ($user->hasVerifiedEmail()) {
        return response()->json(['message' => 'You have already verified your email.']);
    }
    $user->markEmailAsVerified();
    return response()->json(['message' => 'You have successfully verified your email.']);
})->name('verification.verify');

// Route::get('/email/verify', function () {
//     return view('auth.verify-email');
// })->middleware('auth')->name('verification.notice');
// use Illuminate\Http\Request;
 
// Route::post('/email/verification-notification', function (Request $request) {
//     $request->user()->sendEmailVerificationNotification();
 
//     return back()->with('message', 'Verification link sent!');
// })->middleware(['auth', 'throttle:6,1'])->name('verification.send');