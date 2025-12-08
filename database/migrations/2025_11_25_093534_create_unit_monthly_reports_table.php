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
        Schema::create('unit_monthly_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('unit_id')->index(); // From master_db.m_unit
            $table->unsignedTinyInteger('month');
            $table->year('year');
            $table->decimal('kilometer', 10, 2)->default(0);
            $table->unsignedBigInteger('user_id')->index(); // From master_db.users
            $table->timestamps();

            // Ensure only one report exists per unit per month
            $table->unique(['unit_id', 'month', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_monthly_reports');
    }
};