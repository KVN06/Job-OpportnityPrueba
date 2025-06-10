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
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('link')->nullable();
            $table->string('provider');
            $table->string('category');
            $table->enum('level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->integer('duration')->nullable()->comment('Duration in hours');
            $table->decimal('cost', 10, 2)->nullable();
            $table->string('image_path')->nullable();
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->integer('max_participants')->nullable();
            $table->enum('status', ['draft', 'upcoming', 'ongoing', 'completed', 'cancelled'])->default('draft');
            $table->boolean('certification_available')->default(false);
            $table->timestamps();

            $table->index(['status', 'start_date']);
            $table->index('category');
            $table->index('level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};
