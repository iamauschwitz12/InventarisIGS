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
        Schema::table('barang_rusaks', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['tipe_aset_id']);
            
            // Make column nullable
            $table->unsignedBigInteger('tipe_aset_id')->nullable()->change();
            
            // Add foreign key back with nullable support
            $table->foreign('tipe_aset_id')
                ->references('id')
                ->on('tipe_asets')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang_rusaks', function (Blueprint $table) {
            $table->dropForeign(['tipe_aset_id']);
            
            $table->unsignedBigInteger('tipe_aset_id')->nullable(false)->change();
            
            $table->foreign('tipe_aset_id')
                ->references('id')
                ->on('tipe_asets')
                ->onDelete('cascade');
        });
    }
};
