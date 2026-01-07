<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@winbreaker.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'role' => User::ROLE_ADMIN,
                'is_active' => true,
            ]
        );
    }
}
