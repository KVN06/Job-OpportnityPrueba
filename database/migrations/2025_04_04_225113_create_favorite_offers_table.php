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
            $table->unsignedBigInteger('unemployed_id');
        $table->unsignedBigInteger('job_offer_id');
        $table->timestamp('added_at')->useCurrent();

        $table->foreign('unemployed_id')->references('id')->on('unemployed')->onDelete('cascade');
        $table->foreign('job_offer_id')->references('id')->on('job_offers')->onDelete('cascade');
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
