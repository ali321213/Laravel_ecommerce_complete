<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Settings;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Settings::create([
            'short_des'   => 'Your trusted ecommerce platform.',
            'description' => 'Welcome to our ecommerce platform where you can find top-quality products at the best prices.',
            'photo'       => 'images/logo.png',
            'address'     => '123 Main Street, Lahore, Pakistan',
            'phone'       => '+92 300 1234567',
            'email'       => 'support@example.com',
            'logo'        => 'images/logo.png',
        ]);
    }
}
