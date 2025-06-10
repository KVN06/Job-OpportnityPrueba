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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('company_name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('website')->nullable();
            $table->string('location');
            $table->string('logo')->nullable();
            $table->string('industry');
            $table->integer('size')->default(1);
            $table->integer('founded_year')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->json('social_media')->nullable();
            $table->json('benefits')->nullable();
            $table->json('culture')->nullable();
            $table->enum('status', ['active', 'inactive', 'verified', 'unverified'])->default('unverified');
            $table->timestamps();

            // Indexes for better query performance
            $table->index('status');
            $table->index('industry');
            $table->index(['status', 'industry']);
            $table->fulltext(['company_name', 'description']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
