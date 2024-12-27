<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Test',
            'surname' => 'User',
            'email' => 'test@example.com',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);
    }
}
