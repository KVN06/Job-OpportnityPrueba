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
        if (!Schema::hasTable('user_preferences')) {
            Schema::create('user_preferences', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->boolean('email_notifications')->default(true);
                $table->boolean('push_notifications')->default(true);
                $table->boolean('public_profile')->default(true);
                $table->json('job_preferences')->nullable();
                $table->json('salary_range')->nullable();
                $table->string('language')->default('es');
                $table->string('timezone')->default('America/Mexico_City');
                $table->enum('theme', ['light', 'dark', 'system'])->default('system');
                $table->enum('notification_frequency', ['immediately', 'daily', 'weekly'])->default('daily');
                $table->integer('search_radius')->default(50);
                $table->timestamps();

                $table->unique('user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_preferences');
    }
};
