<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    public function definition(): array
    {
        $serviceTypes = [
            'solicitor', 'mortgage_advisor', 'technical_specialist',
            'surveyor', 'architect', 'financial_advisor',
            'estate_agent', 'insurance'
        ];

        $pricingTypes = ['fixed', 'hourly', 'consultation'];
        $serviceAreas = [
            ['London', 'South East England'],
            ['London', 'Essex', 'Kent'],
            ['London', 'Greater London'],
            ['UK Wide'],
            ['London', 'Surrey', 'Sussex'],
            ['London', 'Home Counties'],
            ['Remote Consultations', 'London'],
        ];

        $serviceType = fake()->randomElement($serviceTypes);
        $pricingType = fake()->randomElement($pricingTypes);
        
        // Adjust pricing based on type
        $pricing = match($pricingType) {
            'hourly' => fake()->numberBetween(50, 300),
            'consultation' => fake()->numberBetween(100, 500),
            default => fake()->numberBetween(200, 2000),
        };

        return [
            'user_id' => User::factory()->serviceProvider(),
            'title' => $this->generateServiceTitle($serviceType),
            'description' => fake()->paragraphs(rand(2, 4), true),
            'service_type' => $serviceType,
            'pricing' => $pricing,
            'pricing_type' => $pricingType,
            'service_areas' => fake()->randomElement($serviceAreas),
            'logo' => null,
            'images' => [],
            'external_url' => fake()->optional(0.8)->url(),
            'is_active' => fake()->boolean(85),
            'is_featured' => fake()->boolean(25),
            'deactivated_at' => null,
            'archived_at' => fake()->optional(0.05)->dateTimeBetween('-1 year', 'now'),
            'view_count' => fake()->numberBetween(0, 800),
            'lead_count' => fake()->numberBetween(0, 80),
        ];
    }

    private function generateServiceTitle(string $serviceType): string
    {
        $titles = [
            'solicitor' => [
                'Property Law & Conveyancing',
                'Legal Services for Property',
                'Conveyancing Specialists',
                'Property Legal Advice',
                'Residential Property Law'
            ],
            'mortgage_advisor' => [
                'Mortgage Advisory Services',
                'Independent Mortgage Advice',
                'First Time Buyer Mortgages',
                'Buy-to-Let Mortgage Specialists',
                'Remortgage Experts'
            ],
            'technical_specialist' => [
                'Technical Due Diligence',
                'Property Technical Assessments',
                'Building Systems Analysis',
                'MEP Consulting Services',
                'Structural Engineering'
            ],
            'surveyor' => [
                'Building Surveys & Inspections',
                'Property Survey Services',
                'RICS Chartered Surveyors',
                'Homebuyer Reports',
                'Commercial Property Surveys'
            ],
            'architect' => [
                'Architectural Design Services',
                'Planning & Design Consultancy',
                'Residential Architecture',
                'Planning Applications',
                'Building Design Solutions'
            ],
            'financial_advisor' => [
                'Financial Planning Services',
                'Property Investment Advice',
                'Wealth Management',
                'Pension & Investment Planning',
                'Independent Financial Advice'
            ],
            'estate_agent' => [
                'Estate Agency Services',
                'Property Sales & Lettings',
                'Local Property Experts',
                'Property Marketing',
                'Residential Sales'
            ],
            'insurance' => [
                'Property Insurance',
                'Building & Contents Insurance',
                'Landlord Insurance',
                'Commercial Property Insurance',
                'Insurance Advisory Services'
            ],
        ];

        return fake()->randomElement($titles[$serviceType] ?? ['Professional Services']);
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