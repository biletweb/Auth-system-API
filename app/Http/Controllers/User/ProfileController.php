<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\NewPasswordRequest;
use App\Http\Requests\User\UpdatePersonalInfoRequest;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'user' => $request->user()->only('id', 'name', 'surname', 'email', 'role'),
            'access_token' => $request->bearerToken(),
        ]);
    }

    public function changePassword(NewPasswordRequest $request)
    {
        if ($request->validated()) {
            if (password_verify($request->password, $request->user()->password)) {
                return response()->json([
                    'error' => 'New password cannot be the same as the current password.',
                ]);
            }

            $request->user()->update([
                'password' => bcrypt($request->password),
            ]);

            return response()->json([
                'message' => 'Password changed successfully.',
            ]);
        }
    }

    public function updatePersonalInfo(UpdatePersonalInfoRequest $request)
    {
        if ($request->validated()) {
            if ($request->name === $request->user()->name) {
                return response()->json([
                    'error' => 'Name cannot be the same.',
                ]);
            }
            if ($request->surname === $request->user()->surname) {
                return response()->json([
                    'error' => 'Surname cannot be the same.',
                ]);
            }
        }
    }
}
