<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class HRUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'hr@winbreaker.com'],
            [
                'name' => 'HR Manager',
                'password' => Hash::make('hr123'),
                'role' => User::ROLE_HR,
                'is_admin' => false,
                'is_active' => true,
            ]
        );
    }
}
