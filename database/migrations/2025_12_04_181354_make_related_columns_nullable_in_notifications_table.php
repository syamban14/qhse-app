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
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('related_type')->nullable()->change();
            $table->unsignedBigInteger('related_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // This will fail if there are any rows with null values.
            // Manually update data before rolling back.
            $table->string('related_type')->nullable(false)->change();
            $table->unsignedBigInteger('related_id')->nullable(false)->change();
        });
    }
};
