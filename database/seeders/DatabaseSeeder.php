<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            [
                'name' => 'Admin User',
                'email' => 'admin@admin.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Regular User',
                'email' => 'user@user.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'user',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        // User::factory()->count(10)->create();
        $this->call([
            ProductCategorySeeder::class,
            ProductSeeder::class,
            CategorySeeder::class,
            SiteSettingSeeder::class,
            SettingSeeder::class,
            PostCategorySeeder::class,
            PostTagSeeder::class,
            PostSeeder::class,
            PostCommentSeeder::class,
        ]);
    }
}
