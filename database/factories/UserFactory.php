<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => fake()->randomElement(['developer', 'service_provider']),
            'is_active' => fake()->boolean(90), // 90% chance of being active
            'company' => fake()->company(),
            'bio' => fake()->optional(0.7)->paragraph(),
            'phone' => fake()->optional(0.8)->phoneNumber(),
            'website' => fake()->optional(0.6)->url(),
            'custom_rate_per_lead' => fake()->optional(0.3)->randomFloat(2, 3, 15),
            'last_payment_date' => fake()->optional(0.4)->dateTimeBetween('-6 months', 'now'),
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function developer(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'developer',
        ]);
    }

    public function serviceProvider(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'service_provider',
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}