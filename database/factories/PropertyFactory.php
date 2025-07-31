<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory
{
    public function definition(): array
    {
        $propertyTypes = ['Apartment', 'House', 'Townhouse', 'Studio', 'Penthouse', 'Flat', 'Maisonette'];
        $locations = [
            'Camden, London', 'Shoreditch, London', 'Canary Wharf, London',
            'Kensington, London', 'Richmond, London', 'Clapham, London',
            'Islington, London', 'Hackney, London', 'Greenwich, London',
            'Wimbledon, London', 'Chelsea, London', 'Hammersmith, London'
        ];

        $bedrooms = fake()->numberBetween(0, 5);
        $bathrooms = $bedrooms > 0 ? fake()->numberBetween(1, min($bedrooms + 1, 4)) : 1;
        
        return [
            'user_id' => User::factory()->developer(),
            'title' => fake()->sentence(rand(3, 8)),
            'description' => fake()->paragraphs(rand(2, 4), true),
            'price' => fake()->numberBetween(200000, 2000000),
            'location' => fake()->randomElement($locations),
            'property_type' => fake()->randomElement($propertyTypes),
            'bedrooms' => $bedrooms,
            'bathrooms' => $bathrooms,
            'area' => fake()->randomFloat(1, 30, 300),
            'images' => [],
            'external_link' => fake()->optional(0.7)->url(),
            'is_active' => fake()->boolean(85),
            'is_featured' => fake()->boolean(20),
            'deactivated_at' => null,
            'archived_at' => fake()->optional(0.05)->dateTimeBetween('-1 year', 'now'),
            'view_count' => fake()->numberBetween(0, 500),
            'lead_count' => fake()->numberBetween(0, 50),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
            'archived_at' => null,
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
            'is_active' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
            'deactivated_at' => fake()->dateTimeBetween('-3 months', 'now'),
        ]);
    }

    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
            'archived_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ]);
    }
}