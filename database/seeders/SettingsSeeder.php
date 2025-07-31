<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'default_rate_per_lead',
                'value' => '5.00',
                'type' => 'decimal',
                'description' => 'Default rate charged per lead in GBP',
                'is_public' => false,
            ],
            [
                'key' => 'invoice_due_days',
                'value' => '30',
                'type' => 'integer',
                'description' => 'Number of days until invoice is due',
                'is_public' => false,
            ],
            [
                'key' => 'deactivation_grace_days',
                'value' => '7',
                'type' => 'integer',
                'description' => 'Grace period before deactivating overdue accounts',
                'is_public' => false,
            ],
            [
                'key' => 'archive_after_weeks',
                'value' => '4',
                'type' => 'integer',
                'description' => 'Weeks after which inactive listings are archived',
                'is_public' => false,
            ],
            [
                'key' => 'company_name',
                'value' => 'IMPACTS Referral Services',
                'type' => 'string',
                'description' => 'Company name for invoices and communications',
                'is_public' => true,
            ],
            [
                'key' => 'company_email',
                'value' => 'admin@impacts.com',
                'type' => 'string',
                'description' => 'Company contact email',
                'is_public' => true,
            ],
            [
                'key' => 'company_address',
                'value' => '123 Business Street, London, UK',
                'type' => 'string',
                'description' => 'Company address for invoices',
                'is_public' => false,
            ],
            [
                'key' => 'vat_number',
                'value' => 'GB123456789',
                'type' => 'string',
                'description' => 'Company VAT registration number',
                'is_public' => false,
            ],
            [
                'key' => 'tax_rate',
                'value' => '0.20',
                'type' => 'decimal',
                'description' => 'VAT/Tax rate (20% = 0.20)',
                'is_public' => false,
            ],
            [
                'key' => 'enable_email_notifications',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Enable email notifications',
                'is_public' => false,
            ],
            [
                'key' => 'enable_weekly_reports',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Enable weekly analytics reports',
                'is_public' => false,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}