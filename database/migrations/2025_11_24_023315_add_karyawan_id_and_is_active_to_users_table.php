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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('karyawan_id')
                  ->after('id')
                  ->nullable()
                  ->unique();

            $table->boolean('is_active')->after('remember_token')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Then drop the columns
            $table->dropColumn(['karyawan_id', 'is_active']);
        });
    }
};