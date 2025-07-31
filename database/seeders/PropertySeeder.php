<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $developers = User::where('role', 'developer')->get();

        if ($developers->isEmpty()) {
            return;
        }

        $sampleProperties = [
            [
                'title' => 'Luxury Waterfront Apartment',
                'description' => 'Stunning 2-bedroom apartment with panoramic river views. High-end finishes throughout, modern kitchen, and access to building amenities including gym and concierge.',
                'price' => 750000,
                'location' => 'Canary Wharf, London',
                'property_type' => 'Apartment',
                'bedrooms' => 2,
                'bathrooms' => 2,
                'area' => 85.5,
                'is_featured' => true,
                'view_count' => rand(50, 200),
                'lead_count' => rand(5, 25),
            ],
            [
                'title' => 'Victorian Townhouse Conversion',
                'description' => 'Beautifully converted Victorian townhouse in prime location. Original features preserved with modern additions. Perfect for first-time buyers or investors.',
                'price' => 425000,
                'location' => 'Camden, London',
                'property_type' => 'Townhouse',
                'bedrooms' => 3,
                'bathrooms' => 2,
                'area' => 120.0,
                'is_featured' => false,
                'view_count' => rand(30, 150),
                'lead_count' => rand(3, 18),
            ],
            [
                'title' => 'Modern Studio in Tech Hub',
                'description' => 'Contemporary studio apartment in the heart of the tech district. Perfect for young professionals, close to transport links and amenities.',
                'price' => 285000,
                'location' => 'Shoreditch, London',
                'property_type' => 'Studio',
                'bedrooms' => 0,
                'bathrooms' => 1,
                'area' => 45.0,
                'is_featured' => true,
                'view_count' => rand(80, 300),
                'lead_count' => rand(8, 35),
            ],
            [
                'title' => 'Family Home with Garden',
                'description' => 'Spacious 4-bedroom family home with large garden. Recently renovated kitchen and bathrooms. Quiet residential street with excellent schools nearby.',
                'price' => 650000,
                'location' => 'Richmond, London',
                'property_type' => 'House',
                'bedrooms' => 4,
                'bathrooms' => 3,
                'area' => 180.5,
                'is_featured' => false,
                'view_count' => rand(40, 180),
                'lead_count' => rand(4, 20),
            ],
            [
                'title' => 'Penthouse with Terrace',
                'description' => 'Exclusive penthouse with private terrace offering 360-degree city views. Premium finishes, smart home technology, and dedicated parking.',
                'price' => 1250000,
                'location' => 'Kensington, London',
                'property_type' => 'Penthouse',
                'bedrooms' => 3,
                'bathrooms' => 3,
                'area' => 150.0,
                'is_featured' => true,
                'view_count' => rand(25, 100),
                'lead_count' => rand(2, 12),
            ],
        ];

        foreach ($sampleProperties as $index => $propertyData) {
            $developer = $developers->get($index % $developers->count());
            
            Property::create(array_merge($propertyData, [
                'user_id' => $developer->id,
                'external_link' => 'https://example.com/property-' . ($index + 1),
                'images' => [],
            ]));
        }

        // Create additional random properties
        Property::factory()->count(20)->create();
    }
}