<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // As agreed, clear any existing data in the column to prevent type conversion errors.
        DB::table('accidents')->whereNotNull('equipment_type')->update(['equipment_type' => null]);

        Schema::table('accidents', function (Blueprint $table) {
            // First, rename the column to its new, correct name.
            $table->renameColumn('equipment_type', 'm_unit_id');
        });

        // Use a raw statement for PostgreSQL compatibility to change the type,
        // including the required USING clause.
        DB::statement('ALTER TABLE accidents ALTER COLUMN m_unit_id TYPE BIGINT USING (m_unit_id::bigint)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // To reverse, first change the type back to string using a raw statement.
        DB::statement('ALTER TABLE accidents ALTER COLUMN m_unit_id TYPE VARCHAR(255)');

        // Then, rename it back to the original name.
        Schema::table('accidents', function (Blueprint $table) {
            $table->renameColumn('m_unit_id', 'equipment_type');
        });
    }
};
