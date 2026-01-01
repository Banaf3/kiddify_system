<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $admins = [
            [
                'name' => 'khaled',
                'email' => 'khaled.q557@gmail.com',
                'password' => Hash::make('123456123'),
                'phone_number' => '1234567890',
                'gender' => 'male',
                'date_of_birth' => '1990-01-01',
                'address' => '123 Admin Street',
                'role' => 'admin',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'amirah',
                'email' => 'amirahhannanhasan@gmail.com',
                'password' => Hash::make('123456123'),
                'phone_number' => '1234567890',
                'gender' => 'male',
                'date_of_birth' => '1990-01-01',
                'address' => '123 Admin Street',
                'role' => 'admin',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'atirah',
                'email' => 'atirahhusna873@gmail.com',
                'password' => Hash::make('123456123'),
                'phone_number' => '12345678900',
                'gender' => 'male',
                'date_of_birth' => '1990-01-01',
                'address' => '123 Admin Street',
                'role' => 'admin',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sleelyas',
                'email' => 'sleelyas@gmail.com',
                'password' => Hash::make('123456123'),
                'phone_number' => '1234567890',
                'gender' => 'male',
                'date_of_birth' => '1990-01-01',
                'address' => '123 Admin Street',
                'role' => 'admin',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($admins as $admin) {
            // Check if user already exists
            $exists = DB::table('users')->where('email', $admin['email'])->exists();

            if (!$exists) {
                DB::table('users')->insert($admin);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->whereIn('email', [
            'khaled.q557@gmail.com',
            'amirahhannanhasan@gmail.com',
            'atirahhusna873@gmail.com',
            'sleelyas@gmail.com',
        ])->delete();
    }
};
