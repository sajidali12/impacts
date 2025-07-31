<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string('viewable_type');
            $table->unsignedBigInteger('viewable_id');
            $table->string('ip_address', 45);
            $table->string('user_agent')->nullable();
            $table->string('referrer')->nullable();
            $table->json('session_data')->nullable();
            $table->timestamp('viewed_at');
            $table->timestamps();

            $table->index(['viewable_type', 'viewable_id']);
            $table->index('viewed_at');
            $table->index(['ip_address', 'viewed_at']);
        });

        Schema::create('weekly_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('week_start');
            $table->date('week_end');
            $table->integer('total_views')->default(0);
            $table->integer('total_leads')->default(0);
            $table->integer('unique_visitors')->default(0);
            $table->decimal('conversion_rate', 5, 2)->default(0); // percentage
            $table->json('detailed_data')->nullable(); // breakdown by property/service
            $table->timestamps();

            $table->unique(['user_id', 'week_start']);
            $table->index(['user_id', 'week_start']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_views');
        Schema::dropIfExists('weekly_analytics');
    }
};