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
        Schema::create('engineering', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_rusak_id')->constrained('barang_rusaks')->cascadeOnDelete();
            $table->enum('status', [
                'sedang_diperiksa',
                'menunggu_antrian',
                'sedang_diperbaiki',
                'telah_diperbaiki'
            ])->default('sedang_diperiksa');
            $table->text('keterangan')->nullable();
            $table->date('tgl_mulai_perbaikan')->nullable();
            $table->date('tgl_selesai_perbaikan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engineering');
    }
};
