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
        Schema::create('dana_bos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ruang_id')->constrained('ruangs')->cascadeOnDelete();
            $table->foreignId('lantai_id')->constrained('lantais')->cascadeOnDelete();
            $table->foreignId('tipe_aset_dana_bos_id')->constrained('tipe_aset_dana_bos')->cascadeOnDelete();
            $table->foreignId('gedung_id')->constrained('gedungs')->cascadeOnDelete();
            $table->string('nama_barang');
            $table->string('kode_inventaris');
            $table->string('no_seri');
            $table->integer('harga')->nullable();
            $table->date('tgl_beli');
            $table->string('img')->nullable();
            $table->integer('jumlah');
            $table->string('qrcode')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dana_bos');
    }
};
