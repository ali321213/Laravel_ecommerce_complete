<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ProductCategorySeeder::class,
            ProductSeeder::class,
            CategorySeeder::class,
            SiteSettingSeeder::class,
            SettingSeeder::class,

        ]);
        User::factory()->count(10)->create();
    }
}
