<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Site Admin',
                'password' => Hash::make('password'),
                'role' => UserRole::ADMIN,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'editor@example.com'],
            [
                'name' => 'Lead Editor',
                'password' => Hash::make('password'),
                'role' => UserRole::EDITOR,
                'email_verified_at' => now(),
            ]
        );

        User::factory(3)->create();
    }
}

