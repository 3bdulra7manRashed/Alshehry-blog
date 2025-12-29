<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $name = env('ADMIN_NAME');
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (!$name || !$email || !$password) {
             return;
        }

        $role = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
                'is_admin' => true,
                'is_super_admin' => true,
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        if (!$user->hasRole('admin')) {
            $user->assignRole($role);
        }

        $this->command->info("✓ Admin user created (ID: 1) with Role: Admin");
    }
}