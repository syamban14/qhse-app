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
        // This addresses a foreign key violation that occurs because the 'violations' table (in the default 'pgsql' connection)
        // cannot have a native DB foreign key constraint to the 'users' table (in the 'pgsql_master' connection).
        // We are removing the constraint and relying on application logic for integrity.
        Schema::table('violations', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('violations', function (Blueprint $table) {
            // Attempt to restore the foreign key on rollback.
            // Note: This will fail if the databases are still separate, but it is the correct inverse operation.
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
};