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
        Schema::table('lantais', function (Blueprint $table) {
            $table->dropUnique(['lantai']);
            $table->unique(['gedung_id', 'lantai']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lantais', function (Blueprint $table) {
            $table->dropUnique(['gedung_id', 'lantai']);
            $table->unique('lantai');
        });
    }
};
