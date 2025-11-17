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
        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('auditor_id')->constrained('users')->onDelete('cascade');
            $table->date('schedule_date');
            $table->string('status')->default('scheduled'); // e.g., 'scheduled', 'in_progress', 'completed'
            $table->timestamps();
        });

        Schema::create('audit_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained('audits')->onDelete('cascade');
            $table->text('item_question');
            $table->boolean('is_compliant')->nullable();
            $table->text('notes')->nullable();
            $table->string('evidence_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_items');
        Schema::dropIfExists('audits');
    }
};
