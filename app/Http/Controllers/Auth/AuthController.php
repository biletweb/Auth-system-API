<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        if ($request->validated()) {
            $user = User::create([
                'name' => $request->name,
                'surname' => $request->surname,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            event(new Registered($user));

            return response()->json([
                'message' => 'Account has been created successfully! Please confirm your email. We have sent a security code to the address you provided.',
            ]);
        }
    }

    public function login(LoginRequest $request)
    {
        if ($request->validated()) {
            if (auth()->attempt($request->only('email', 'password'))) {
                return response()->json([
                    'message' => 'You have successfully logged in.',
                    'user' => auth()->user()->only('id', 'name', 'surname', 'email', 'email_verified_at', 'role', 'locale'),
                    'access_token' => auth()->user()->createToken('authToken')->plainTextToken,
                ]);
            } else {
                return response()->json([
                    'error' => 'Invalid credentials.',
                ]);
            }
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'You have successfully logged out.',
        ]);
    }

    public function confirmEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['warning' => 'You have already verified your email.']);
        }
        if ($request->verification_code !== $request->user()->verification_code) {
            return response()->json(['error' => 'Invalid verification code.']);
        }

        $request->user()->markEmailAsVerified();
        $request->user()->update([
            'verification_code' => null,
        ]);

        return response()->json(['message' => 'You have successfully verified your email.']);
    }

    public function resendEmail(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification code has been sent.']);
    }
}
