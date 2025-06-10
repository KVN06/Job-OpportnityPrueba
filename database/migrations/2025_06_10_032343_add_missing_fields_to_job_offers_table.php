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
        Schema::table('job_offers', function (Blueprint $table) {
            $table->enum('contract_type', ['tiempo_completo', 'medio_tiempo', 'proyecto', 'practicas'])->default('tiempo_completo');
            $table->enum('experience_level', ['junior', 'medio', 'senior', 'lead'])->default('junior');
            $table->date('application_deadline')->nullable();
            $table->boolean('remote_work')->default(false);
            $table->json('required_skills')->nullable();
            $table->json('benefits')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_offers', function (Blueprint $table) {
            $table->dropColumn([
                'contract_type',
                'experience_level', 
                'application_deadline',
                'remote_work',
                'required_skills',
                'benefits'
            ]);
        });
    }
};
