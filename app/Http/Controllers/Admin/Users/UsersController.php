<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;

class UsersController extends Controller
{
    public function index()
    {
        return response()->json([
            'users' => User::select('id', 'name', 'surname', 'email', 'role', 'locale', 'created_at')->get(),
        ]);
    }
}
