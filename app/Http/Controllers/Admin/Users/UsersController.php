<?php

namespace App\Http\Controllers\Admin\Users;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
}
