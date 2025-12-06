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
        Schema::table('violations', function (Blueprint $table) {
            $table->renameColumn('user_id', 'violator_id');
            $table->string('violator_type')->after('violator_id');
            $table->index(['violator_id', 'violator_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('violations', function (Blueprint $table) {
            $table->dropIndex(['violator_id', 'violator_type']);
            $table->dropColumn('violator_type');
            $table->renameColumn('violator_id', 'user_id');
        });
    }
};
