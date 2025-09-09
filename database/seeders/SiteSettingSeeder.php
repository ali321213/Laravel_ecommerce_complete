<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteSetting;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'name' => 'short_des',
                'value' => 'Your trusted ecommerce platform.',
                'description' => 'Short site description',
            ],
            [
                'name' => 'description',
                'value' => 'Welcome to our ecommerce platform where you can find top-quality products at the best prices.',
                'description' => 'Full site description',
            ],
            [
                'name' => 'photo',
                'value' => 'images/logo.png',
                'description' => 'Main site image',
            ],
            [
                'name' => 'address',
                'value' => '123 Main Street, Lahore, Pakistan',
                'description' => 'Company address',
            ],
            [
                'name' => 'phone',
                'value' => '+92 300 1234567',
                'description' => 'Contact phone number',
            ],
            [
                'name' => 'email',
                'value' => 'support@example.com',
                'description' => 'Support email address',
            ],
            [
                'name' => 'logo',
                'value' => 'images/logo.png',
                'description' => 'Site logo',
            ],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['name' => $setting['name']],
                ['value' => $setting['value'], 'description' => $setting['description']]
            );
        }
    }
}
