<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'user' => $request->user()->only('id', 'name', 'email', 'role'),
            'access_token' => $request->bearerToken(),
        ]);
    }
}
