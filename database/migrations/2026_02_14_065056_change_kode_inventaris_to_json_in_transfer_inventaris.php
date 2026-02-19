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
        Schema::table('transfer_inventaris', function (Blueprint $table) {
            $table->json('kode_inventaris')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transfer_inventaris', function (Blueprint $table) {
            $table->string('kode_inventaris')->change();
        });
    }
};
