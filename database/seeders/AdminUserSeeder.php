<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@iium.edu.my'],
            [
                'name' => 'System Admin',
                'student_id' => 'ADMIN001',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );
    }
}
