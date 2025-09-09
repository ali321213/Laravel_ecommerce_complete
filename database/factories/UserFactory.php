<?php

namespace Database\Factories;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;
    public function definition(): array
    {
        $password = Hash::make('password');
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => $password,
            'remember_token' => Str::random(10),
            'role' => $this->faker->randomElement(['user', 'admin']),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'provider' => null,
            'provider_id' => null,
            'current_team_id' => null,
            'profile_photo_path' => $this->faker->imageUrl(200, 200),
        ];
    }

    /* Indicate that the user’s email address is unverified */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
