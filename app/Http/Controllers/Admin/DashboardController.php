<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalAdminUsers = User::where('role', 'admin')->count();
        $totalUnverifiedEmailUsers = User::where('email_verified_at', null)->count();

        return response()->json([
            'totalUsers' => $totalUsers,
            'totalAdminUsers' => $totalAdminUsers,
            'totalUnverifiedEmailUsers' => $totalUnverifiedEmailUsers,
        ]);
    }
}
