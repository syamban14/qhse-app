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
            // The foreign key constraint name is 'accidents_user_id_foreign'
            $table->dropForeign('accidents_user_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accidents', function (Blueprint $table) {
            // Re-create the foreign key pointing to the original (but incorrect) table for reversibility
            $table->foreign('user_id', 'accidents_user_id_foreign')->references('id')->on('users_old');
        });
    }
};