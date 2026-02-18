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
        Schema::table('sekolahs', function (Blueprint $table) {
            $table->string('no_invoice')->nullable()->after('kode_inventaris');
            $table->string('jenjang')->nullable()->after('no_invoice');
            $table->foreignId('tipe_aset_kategori_id')->nullable()->after('tipe_aset_id')->constrained('tipe_aset_kategoris')->cascadeOnDelete();
        });

        Schema::table('print_histories', function (Blueprint $table) {
            $table->foreignId('sekolah_id')->nullable()->after('pribadi_id')->constrained('sekolahs')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sekolahs', function (Blueprint $table) {
            $table->dropForeign(['tipe_aset_kategori_id']);
            $table->dropColumn(['no_invoice', 'jenjang', 'tipe_aset_kategori_id']);
        });

        Schema::table('print_histories', function (Blueprint $table) {
            $table->dropForeign(['sekolah_id']);
            $table->dropColumn('sekolah_id');
        });
    }
};
