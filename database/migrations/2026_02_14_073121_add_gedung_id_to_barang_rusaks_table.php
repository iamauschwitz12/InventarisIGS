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
        Schema::table('barang_rusaks', function (Blueprint $table) {
            $table->foreignId('gedung_id')->nullable()->after('id')->constrained('gedungs')->cascadeOnDelete();
            $table->json('kode_inventaris')->nullable()->after('inventaris_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang_rusaks', function (Blueprint $table) {
            $table->dropForeign(['gedung_id']);
            $table->dropColumn(['gedung_id', 'kode_inventaris']);
        });
    }
};
