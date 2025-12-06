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
        Schema::create('storing_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_monthly_report_id')->constrained('unit_monthly_reports')->cascadeOnDelete();
            $table->date('event_date');
            $table->time('event_time')->nullable();
            $table->unsignedTinyInteger('week_of_month'); // 1, 2, 3, 4, 5
            $table->string('location');
            $table->text('description');
            $table->unsignedBigInteger('user_id')->index(); // From master_db.users
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storing_events');
    }
};