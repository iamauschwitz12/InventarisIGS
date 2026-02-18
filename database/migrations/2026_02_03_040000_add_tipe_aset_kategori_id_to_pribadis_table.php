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
        Schema::table('pribadis', function (Blueprint $table) {
            $table->foreignId('tipe_aset_kategori_id')->nullable()->constrained('tipe_aset_kategoris')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pribadis', function (Blueprint $table) {
            $table->dropForeign(['tipe_aset_kategori_id']);
            $table->dropColumn('tipe_aset_kategori_id');
        });
    }
};
