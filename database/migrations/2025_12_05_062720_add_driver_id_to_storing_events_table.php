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
        Schema::table('storing_events', function (Blueprint $table) {
            // Adding the column without a foreign key constraint
            // as cross-database constraints are not supported this way.
            // The relationship will be maintained at the application level.
            $table->unsignedBigInteger('driver_id')->nullable()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('storing_events', function (Blueprint $table) {
            $table->dropColumn('driver_id');
        });
    }
};