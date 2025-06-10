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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('unemployed_id');
            $table->unsignedBigInteger('job_offer_id');
            $table->text('cover_letter')->nullable();
            $table->string('resume_path')->nullable();
            $table->enum('status', ['pending', 'reviewing', 'interviewed', 'accepted', 'rejected', 'withdrawn'])->default('pending');
            $table->timestamp('application_date')->useCurrent();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('unemployed_id')->references('id')->on('unemployeds')->onDelete('cascade');
            $table->foreign('job_offer_id')->references('id')->on('job_offers')->onDelete('cascade');
            
            // Ensure one application per job per user
            $table->unique(['unemployed_id', 'job_offer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
