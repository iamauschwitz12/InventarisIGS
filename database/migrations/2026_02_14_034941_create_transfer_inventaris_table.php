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
        Schema::create('transfer_inventaris', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_inventaris'); // pribadi, sekolah, dana_bos
            $table->unsignedBigInteger('sumber_id'); // ID barang
            $table->foreignId('gedung_asal_id')->constrained('gedungs')->cascadeOnDelete();
            $table->foreignId('gedung_tujuan_id')->constrained('gedungs')->cascadeOnDelete();
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
        Schema::dropIfExists('transfer_inventaris');
    }
};
