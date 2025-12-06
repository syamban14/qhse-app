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
        Schema::table('violations', function (Blueprint $table) {
            // Simply change the column to nullable, assuming the foreign key was already dropped by a prior migration
            $table->foreignId('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('violations', function (Blueprint $table) {
            // Revert to not nullable
            // This will fail if there are null values in the user_id column when rolling back.
            // Ensure no null user_id values exist before running rollback for this migration.
            $table->foreignId('user_id')->nullable(false)->change();
            // We are not re-adding the foreign key constraint as it caused issues and was explicitly removed in a prior migration.
        });
    }
};
