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
        Schema::connection('pgsql_master')->create('m_drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('m_karyawan')->onDelete('cascade')->unique();
            $table->enum('driver_category', ['DUMPTRUCK', 'TRAILER', 'PROJECT']);
            $table->string('sim_type')->nullable();
            $table->date('sim_expiry_date')->nullable();
            $table->enum('status', ['ACTIVE', 'SUSPENDED', 'INACTIVE'])->default('ACTIVE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_drivers');
    }
};
