<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::updateOrCreate(
            ['email' => 'admin@impacts.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
                'company' => 'IMPACTS Referral Services',
                'email_verified_at' => now(),
            ]
        );

        // Create marketing user
        User::updateOrCreate(
            ['email' => 'marketing@impacts.com'],
            [
                'name' => 'Marketing Manager',
                'password' => Hash::make('password'),
                'role' => 'marketing',
                'is_active' => true,
                'company' => 'IMPACTS Referral Services',
                'email_verified_at' => now(),
            ]
        );

        // Create sample developer
        User::updateOrCreate(
            ['email' => 'developer@example.com'],
            [
                'name' => 'John Developer',
                'password' => Hash::make('password'),
                'role' => 'developer',
                'is_active' => true,
                'company' => 'Prime Properties Ltd',
                'bio' => 'Experienced property developer specializing in residential developments.',
                'phone' => '+44 20 1234 5678',
                'website' => 'https://primeproperties.co.uk',
                'custom_rate_per_lead' => 7.50,
                'email_verified_at' => now(),
            ]
        );

        // Create sample service provider
        User::updateOrCreate(
            ['email' => 'service@example.com'],
            [
                'name' => 'Jane Service Provider',
                'password' => Hash::make('password'),
                'role' => 'service_provider',
                'is_active' => true,
                'company' => 'Premier Legal Services',
                'bio' => 'Qualified solicitor with 15+ years experience in property law.',
                'phone' => '+44 20 9876 5432',
                'website' => 'https://premierlegal.co.uk',
                'custom_rate_per_lead' => 12.00,
                'email_verified_at' => now(),
            ]
        );

        // Create additional test users
        User::factory()->count(5)->create([
            'role' => 'developer',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        User::factory()->count(5)->create([
            'role' => 'service_provider',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }
}