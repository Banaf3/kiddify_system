<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin users - only if they don't exist
        $admins = [
            [
                'name' => 'hamed',
                'email' => 'khaled.q557@gmail.com',
                'password' => '123456123',
                'phone_number' => '1234567890',
            ],
            // Add more admins here as needed
            // [
            //     'name' => 'another admin',
            //     'email' => 'admin2@gmail.com',
            //     'password' => 'password123',
            //     'phone_number' => '0987654321',
            // ],
        ];

        foreach ($admins as $admin) {
            User::firstOrCreate(
                ['email' => $admin['email']], // Search by email
                [
                    'name' => $admin['name'],
                    'password' => Hash::make($admin['password']),
                    'phone_number' => $admin['phone_number'],
                    'gender' => 'male',
                    'date_of_birth' => '1990-01-01',
                    'address' => '123 Admin Street',
                    'role' => 'admin',
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
