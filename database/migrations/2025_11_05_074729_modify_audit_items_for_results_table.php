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
        Schema::table('audit_items', function (Blueprint $table) {
            $table->dropColumn('is_compliant');
            $table->dropColumn('notes');
            $table->string('result')->nullable()->after('item_question');
            $table->text('remarks')->nullable()->after('result');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_items', function (Blueprint $table) {
            $table->boolean('is_compliant')->nullable();
            $table->text('notes')->nullable();
            $table->dropColumn('result');
            $table->dropColumn('remarks');
        });
    }
};
