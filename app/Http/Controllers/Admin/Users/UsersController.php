<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;

class UsersController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'warning' => 'You do not have permission to view this page.',
            ]);
        }

        return response()->json([
            'users' => User::select('id', 'name', 'surname', 'email', 'role', 'locale', 'created_at', 'email_verified_at')->orderByDesc('id')->get(),
        ]);
    }
}
