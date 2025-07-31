<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $serviceProviders = User::where('role', 'service_provider')->get();

        if ($serviceProviders->isEmpty()) {
            return;
        }

        $sampleServices = [
            [
                'title' => 'Property Law & Conveyancing',
                'description' => 'Expert legal services for property transactions. Experienced in residential and commercial conveyancing, lease agreements, and property disputes. Fast turnaround guaranteed.',
                'service_type' => 'solicitor',
                'pricing' => 800.00,
                'pricing_type' => 'fixed',
                'service_areas' => ['London', 'South East England', 'Birmingham'],
                'external_url' => 'https://premierlegal.co.uk',
                'is_featured' => true,
                'view_count' => rand(100, 400),
                'lead_count' => rand(10, 50),
            ],
            [
                'title' => 'Mortgage Advisory Services',
                'description' => 'Independent mortgage advice for first-time buyers, remortgages, and buy-to-let investments. Access to exclusive rates from over 150 lenders.',
                'service_type' => 'mortgage_advisor',
                'pricing' => 500.00,
                'pricing_type' => 'fixed',
                'service_areas' => ['London', 'Essex', 'Kent', 'Surrey'],
                'external_url' => 'https://mortgageexperts.co.uk',
                'is_featured' => true,
                'view_count' => rand(150, 500),
                'lead_count' => rand(15, 60),
            ],
            [
                'title' => 'Building Surveys & Inspections',
                'description' => 'Comprehensive building surveys and property inspections. RICS qualified surveyors providing detailed reports for residential and commercial properties.',
                'service_type' => 'surveyor',
                'pricing' => 350.00,
                'pricing_type' => 'fixed',
                'service_areas' => ['London', 'Greater London', 'Home Counties'],
                'external_url' => 'https://surveyexperts.co.uk',
                'is_featured' => false,
                'view_count' => rand(80, 300),
                'lead_count' => rand(8, 35),
            ],
            [
                'title' => 'Architectural Design Services',
                'description' => 'Creative architectural solutions for residential and commercial projects. From planning applications to construction drawings and project management.',
                'service_type' => 'architect',
                'pricing' => 150.00,
                'pricing_type' => 'hourly',
                'service_areas' => ['London', 'South East England'],
                'external_url' => 'https://designstudio.co.uk',
                'is_featured' => false,
                'view_count' => rand(60, 250),
                'lead_count' => rand(6, 28),
            ],
            [
                'title' => 'Financial Planning & Investment',
                'description' => 'Holistic financial planning including property investment advice, pension planning, and wealth management. FCA regulated with 20+ years experience.',
                'service_type' => 'financial_advisor',
                'pricing' => 200.00,
                'pricing_type' => 'consultation',
                'service_areas' => ['London', 'South East England', 'Remote Consultations'],
                'external_url' => 'https://wealthadvisors.co.uk',
                'is_featured' => true,
                'view_count' => rand(120, 450),
                'lead_count' => rand(12, 55),
            ],
            [
                'title' => 'Technical Due Diligence',
                'description' => 'Specialist technical assessments for property acquisitions and developments. Structural engineering, MEP reviews, and compliance audits.',
                'service_type' => 'technical_specialist',
                'pricing' => 1200.00,
                'pricing_type' => 'fixed',
                'service_areas' => ['London', 'UK Wide'],
                'external_url' => 'https://techduediligence.co.uk',
                'is_featured' => false,
                'view_count' => rand(40, 180),
                'lead_count' => rand(4, 20),
            ],
        ];

        foreach ($sampleServices as $index => $serviceData) {
            $serviceProvider = $serviceProviders->get($index % $serviceProviders->count());
            
            Service::create(array_merge($serviceData, [
                'user_id' => $serviceProvider->id,
                'images' => [],
            ]));
        }

        // Create additional random services
        Service::factory()->count(15)->create();
    }
}