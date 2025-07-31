<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('service_type'); // solicitor, mortgage_advisor, technical_specialist, etc.
            $table->decimal('pricing', 8, 2)->nullable();
            $table->string('pricing_type')->default('fixed'); // fixed, hourly, consultation
            $table->json('service_areas')->nullable(); // geographical areas served
            $table->string('logo')->nullable();
            $table->json('images')->nullable();
            $table->string('external_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamp('deactivated_at')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->integer('view_count')->default(0);
            $table->integer('lead_count')->default(0);
            $table->timestamps();

            $table->index(['user_id', 'is_active']);
            $table->index('is_active');
            $table->index('service_type');
            $table->index('is_featured');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};