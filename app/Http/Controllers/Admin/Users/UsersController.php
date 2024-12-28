<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\ChangeUserRoleRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'warning' => 'You do not have permission to view this page.',
            ]);
        }

        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 10);

        $users = User::select('id', 'name', 'surname', 'email', 'role', 'locale', 'created_at', 'email_verified_at')
            ->skip($offset)
            ->take($limit)
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'users' => $users,
        ]);
    }

    public function changeRole(ChangeUserRoleRequest $request)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'warning' => 'You do not have permission to view this page.',
            ]);
        }

        $user = User::find($request->input('id'));

        if (! $user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->role = $user->role === 'admin' ? 'user' : 'admin';
        $user->save();

        return response()->json([
            'message' => 'User role changed successfully.',
            'user' => $user->only(['id', 'name', 'surname', 'email', 'role', 'locale', 'created_at', 'email_verified_at']),
        ]);
    }
}
