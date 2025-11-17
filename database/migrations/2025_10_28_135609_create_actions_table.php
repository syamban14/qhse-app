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
        Schema::create('actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incident_id')->nullable()->constrained('incidents')->onDelete('cascade');
            // We'll add audit_id later when the audits table is created
            $table->text('description');
            $table->foreignId('pic_user_id')->constrained('users')->onDelete('cascade');
            $table->date('due_date');
            $table->string('status')->default('open'); // e.g., 'open', 'in_progress', 'completed'
            $table->text('completion_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actions');
    }
};
