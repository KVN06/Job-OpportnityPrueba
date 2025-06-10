<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('favorite_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unemployed_id')->constrained('unemployeds')->onDelete('cascade');
            $table->foreignId('job_offer_id')->constrained()->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->json('notification_preferences')->nullable();
            $table->timestamps();

            // Ensure unique favorites per user
            $table->unique(['unemployed_id', 'job_offer_id']);
            
            // Indexes for better query performance
            $table->index(['unemployed_id', 'created_at']);
            $table->index(['job_offer_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorite_offers');
    }
};
