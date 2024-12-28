<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
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

    public function changeRole(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'warning' => 'You do not have permission to view this page.',
            ]);
        }

        $user = User::find($request->input('id'));
        $user->role === 'admin' ? $user->role = 'user' : $user->role = 'admin';
        $user->save();

        return response()->json([
            'message' => 'User role changed successfully.',
            'user' => $user,
        ]);
    }
}
