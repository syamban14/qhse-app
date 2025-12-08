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
        Schema::table('accidents', function (Blueprint $table) {
            $table->string('employee_payroll_id')->after('user_id')->nullable();
            $table->string('penyebab_dasar')->after('photo_path')->nullable();
            $table->text('penjelasan_penyebab_dasar')->after('penyebab_dasar')->nullable();
            $table->string('penyebab_langsung')->after('penjelasan_penyebab_dasar')->nullable();
            $table->string('kondisi_tidak_aman')->after('penyebab_langsung')->nullable();
            $table->text('kesimpulan')->after('kondisi_tidak_aman')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accidents', function (Blueprint $table) {
            $table->dropColumn([
                'employee_payroll_id',
                'penyebab_dasar',
                'penjelasan_penyebab_dasar',
                'penyebab_langsung',
                'kondisi_tidak_aman',
                'kesimpulan',
            ]);
        });
    }
};