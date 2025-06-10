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
        Schema::table('unemployeds', function (Blueprint $table) {
            $table->integer('experience_years')->default(0);
            $table->enum('experience_level', ['junior', 'medio', 'senior', 'lead'])->default('junior');
            $table->text('skills')->nullable();
            $table->text('education')->nullable();
            $table->string('cv')->nullable();
            $table->boolean('remote_work')->default(false);
            $table->decimal('expected_salary', 10, 2)->nullable();
            $table->text('bio')->nullable();
            $table->enum('availability', ['immediate', 'two_weeks', 'one_month', 'negotiable'])->default('negotiable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('unemployeds', function (Blueprint $table) {
            $table->dropColumn([
                'experience_years',
                'experience_level',
                'skills',
                'education',
                'cv',
                'remote_work',
                'expected_salary',
                'bio',
                'availability'
            ]);
        });
    }
};
