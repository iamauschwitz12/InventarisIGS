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
        Schema::create('barang_rusaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ruang_id')->constrained('ruangs')->cascadeOnDelete();
            $table->foreignId('lantai_id')->constrained('lantais')->cascadeOnDelete();
            $table->foreignId('tipe_aset_id')->nullable()->constrained('tipe_asets')->nullOnDelete();
            $table->string('inventaris_type')->nullable(); // App\Models\Sekolah, etc.
            $table->json('inventaris_id')->nullable();     // array of IDs
            $table->unsignedInteger('jumlah_rusak');
            $table->date('tgl_rusak');
            $table->date('tgl_input');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_rusaks');
    }
};
