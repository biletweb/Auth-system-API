<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function searchUsers(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'warning' => 'You do not have permission to view this page.',
            ]);
        }

        $searchTerm = $request->input('search');

        $users = User::select('id', 'name', 'surname', 'email', 'role', 'locale', 'created_at', 'email_verified_at')
            ->where('name', 'like', '%'.$searchTerm.'%')
            ->orWhere('surname', 'like', '%'.$searchTerm.'%')
            ->orWhere('email', 'like', '%'.$searchTerm.'%')
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'users' => $users,
        ]);
    }
}
