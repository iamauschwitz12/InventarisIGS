<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ruangs', function (Blueprint $table) {
            $table->foreignId('gedung_id')->nullable()->constrained('gedungs')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ruangs', function (Blueprint $table) {
            $table->dropForeign(['gedung_id']);
            $table->dropColumn('gedung_id');
        });
    }
};
