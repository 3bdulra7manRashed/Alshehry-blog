<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class, 
            AdminUserSeeder::class,          
            DeletedUserSeeder::class,         
            UserSeeder::class,     
            CategorySeeder::class, 
            TagSeeder::class,      
            PostSeeder::class,     
            MediaSeeder::class,    
        ]);
    }
}