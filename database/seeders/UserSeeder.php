<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       User::updateOrCreate(
            [
                'email' => env('ADMIN_EMAIL', 'admin@accelerate.com'),
            ],
            [
                'name' => env('ADMIN_NAME', 'Accelerate Admin '),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'accelerate@2025!?229')),
                'role' => 'admin',
            ]
        );
    }
}
