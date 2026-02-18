<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('dana_bos', function (Blueprint $table) {
            $table->foreignId('tipe_aset_id')->nullable()->after('tipe_aset_dana_bos_id')->constrained('tipe_asets')->nullOnDelete();
            $table->foreignId('tipe_aset_kategori_id')->nullable()->after('tipe_aset_id')->constrained('tipe_aset_kategoris')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('dana_bos', function (Blueprint $table) {
            $table->dropForeign(['tipe_aset_id']);
            $table->dropForeign(['tipe_aset_kategori_id']);
            $table->dropColumn(['tipe_aset_id', 'tipe_aset_kategori_id']);
        });
    }
};
