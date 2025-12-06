<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // We use raw SQL here because modifying a column requires the 'doctrine/dbal' package,
        // which is not installed. This is a more direct approach for a one-time fix.
        DB::connection('pgsql_master')->statement('ALTER TABLE m_karyawan ALTER COLUMN id SET NOT NULL');
        DB::connection('pgsql_master')->statement('ALTER TABLE m_karyawan ADD PRIMARY KEY (id)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // It's good practice to make migrations reversible.
        Schema::connection('pgsql_master')->table('m_karyawan', function (Blueprint $table) {
            // The primary key constraint name is typically <table>_pkey in PostgreSQL.
            $table->dropPrimary('m_karyawan_pkey');
        });
        // We might not want to revert the NOT NULL constraint, but for completeness:
        DB::connection('pgsql_master')->statement('ALTER TABLE m_karyawan ALTER COLUMN id DROP NOT NULL');
    }
};