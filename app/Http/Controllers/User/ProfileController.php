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
            'user' => $request->user()->only('id', 'name', 'surname', 'email', 'email_verified_at', 'role', 'locale'),
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
        if ($request->name !== $request->user()->name || $request->surname !== $request->user()->surname) {
            $request->user()->update([
                'name' => $request->name,
                'surname' => $request->surname,
            ]);

            return response()->json([
                'message' => 'Personal information updated successfully.',
            ]);
        } else {
            return response()->json([
                'warning' => 'No changes were made.',
            ]);
        }
    }

    public function deleteAccount(Request $request)
    {
        if ($request->user()->role === 'admin') {
            return response()->json([
                'warning' => 'It is not possible to delete an account with the administrator role. Please change your role before deleting.',
            ]);
        }

        $request->user()->currentAccessToken()->delete();
        $request->user()->delete();

        return response()->json([
            'message' => 'Account deleted successfully.',
        ]);
    }

    public function changeLocale(Request $request)
    {
        $request->user()->update([
            'locale' => $request->locale,
        ]);

        return response()->json([
            'message' => 'Locale changed successfully.',
        ]);
    }
}
