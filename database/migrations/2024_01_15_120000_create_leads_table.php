<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // owner of the property/service
            $table->string('leadable_type'); // property or service
            $table->unsignedBigInteger('leadable_id');
            $table->string('ip_address', 45);
            $table->string('user_agent')->nullable();
            $table->string('referrer')->nullable();
            $table->json('session_data')->nullable();
            $table->decimal('rate_charged', 8, 2); // rate charged for this lead
            $table->boolean('is_invoiced')->default(false);
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['leadable_type', 'leadable_id']);
            $table->index('is_invoiced');
            $table->index(['created_at', 'is_invoiced']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};