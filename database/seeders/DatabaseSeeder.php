<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->count(10)->create();
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
