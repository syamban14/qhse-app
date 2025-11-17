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
        Schema::table('actions', function (Blueprint $table) {
            $table->text('effectiveness_verification_notes')->nullable()->after('completion_notes');
            $table->date('effectiveness_verification_date')->nullable()->after('effectiveness_verification_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('actions', function (Blueprint $table) {
            $table->dropColumn(['effectiveness_verification_notes', 'effectiveness_verification_date']);
        });
    }
};
