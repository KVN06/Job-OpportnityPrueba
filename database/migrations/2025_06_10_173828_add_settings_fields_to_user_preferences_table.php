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
        Schema::table('user_preferences', function (Blueprint $table) {
            // Notification settings
            if (!Schema::hasColumn('user_preferences', 'job_alerts')) {
                $table->boolean('job_alerts')->default(true);
            }
            if (!Schema::hasColumn('user_preferences', 'message_notifications')) {
                $table->boolean('message_notifications')->default(true);
            }
            if (!Schema::hasColumn('user_preferences', 'training_notifications')) {
                $table->boolean('training_notifications')->default(true);
            }
            if (!Schema::hasColumn('user_preferences', 'application_notifications')) {
                $table->boolean('application_notifications')->default(true);
            }
            
            // Privacy settings
            if (!Schema::hasColumn('user_preferences', 'show_email')) {
                $table->boolean('show_email')->default(false);
            }
            if (!Schema::hasColumn('user_preferences', 'show_phone')) {
                $table->boolean('show_phone')->default(false);
            }
            if (!Schema::hasColumn('user_preferences', 'allow_messages')) {
                $table->boolean('allow_messages')->default(true);
            }
            if (!Schema::hasColumn('user_preferences', 'show_activity')) {
                $table->boolean('show_activity')->default(true);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_preferences', function (Blueprint $table) {
            $columnsToRemove = [
                'job_alerts',
                'message_notifications', 
                'training_notifications',
                'application_notifications',
                'show_email',
                'show_phone',
                'allow_messages',
                'show_activity'
            ];
            
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('user_preferences', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
