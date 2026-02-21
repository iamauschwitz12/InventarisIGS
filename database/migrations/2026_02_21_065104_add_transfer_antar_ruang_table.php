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
        Schema::create('transfer_antar_ruangs', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_inventaris'); // pribadi, sekolah, dana_bos
            $table->string('status')->nullable(); // pinjam, pengembalian
            $table->json('sumber_id')->nullable(); // ID barang (array)
            $table->foreignId('gedung_id')->constrained('gedungs')->cascadeOnDelete();
            $table->foreignId('lantai_asal_id')->nullable()->constrained('lantais')->nullOnDelete();
            $table->foreignId('ruang_asal_id')->nullable()->constrained('ruangs')->nullOnDelete();
            $table->foreignId('lantai_tujuan_id')->nullable()->constrained('lantais')->nullOnDelete();
            $table->foreignId('ruang_tujuan_id')->nullable()->constrained('ruangs')->nullOnDelete();
            $table->string('nama_barang');
            $table->json('kode_inventaris')->nullable();
            $table->integer('jumlah_transfer')->default(1);
            $table->date('tanggal_transfer');
            $table->string('keterangan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_antar_ruangs');
    }
};
