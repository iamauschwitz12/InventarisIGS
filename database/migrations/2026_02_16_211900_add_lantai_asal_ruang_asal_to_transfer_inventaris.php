<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transfer_inventaris', function (Blueprint $table) {
            $table->foreignId('lantai_asal_id')->nullable()->after('gedung_asal_id')->constrained('lantais')->nullOnDelete();
            $table->foreignId('ruang_asal_id')->nullable()->after('lantai_asal_id')->constrained('ruangs')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('transfer_inventaris', function (Blueprint $table) {
            $table->dropConstrainedForeignId('ruang_asal_id');
            $table->dropConstrainedForeignId('lantai_asal_id');
        });
    }
};
