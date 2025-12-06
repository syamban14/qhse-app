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
        Schema::connection('pgsql_master')->create('driver_performance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('m_drivers')->onDelete('cascade');
            $table->year('year');
            $table->unsignedTinyInteger('month');
            $table->integer('fatigue_count')->default(0);
            $table->integer('distraction_count')->default(0);
            $table->integer('fov_count')->default(0);
            $table->integer('rest_area_non_compliance_count')->default(0);
            $table->integer('prohibited_hours_violation_count')->default(0);
            $table->integer('accident_count')->default(0);
            $table->integer('general_violation_count')->default(0);
            $table->float('monthly_score')->nullable();
            $table->timestamps();

            $table->unique(['driver_id', 'year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('pgsql_master')->dropIfExists('driver_performance_logs');
    }
};
