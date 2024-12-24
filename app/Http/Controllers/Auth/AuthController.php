<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        if ($request->validated()) {
            User::create([
                'name' => $request->name,
                'surname' => $request->surname,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            return response()->json([
                'message' => 'Account has been created successfully.',
            ]);
        }
    }

    public function login(LoginRequest $request)
    {
        if ($request->validated()) {
            if (auth()->attempt($request->only('email', 'password'))) {
                return response()->json([
                    'message' => 'You have successfully logged in.',
                    'user' => auth()->user()->only('id', 'name', 'email', 'role'),
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
}
