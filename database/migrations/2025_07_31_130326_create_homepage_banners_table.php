<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Welcome to IMPACTS Referral');
            $table->text('subtitle')->default('Connecting property developers with quality services and solutions');
            $table->text('description')->nullable();
            $table->string('background_image')->nullable();
            $table->string('cta_text')->default('Explore Properties');
            $table->string('cta_link')->default('/properties');
            $table->string('secondary_cta_text')->default('Browse Services');
            $table->string('secondary_cta_link')->default('/services');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert default banner
        DB::table('homepage_banners')->insert([
            'title' => 'Welcome to IMPACTS Referral',
            'subtitle' => 'Connecting property developers with quality services and solutions',
            'description' => 'Discover exceptional properties and professional services from our trusted network of developers and service providers.',
            'cta_text' => 'Explore Properties',
            'cta_link' => '/properties',
            'secondary_cta_text' => 'Browse Services',
            'secondary_cta_link' => '/services',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_banners');
    }
};
