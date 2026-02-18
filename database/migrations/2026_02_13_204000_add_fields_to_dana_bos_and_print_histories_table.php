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
        Schema::table('dana_bos', function (Blueprint $table) {
            $table->string('no_invoice')->nullable()->after('kode_inventaris');
            $table->string('jenjang')->nullable()->after('no_invoice');
        });

        Schema::table('print_histories', function (Blueprint $table) {
            $table->foreignId('dana_bos_id')->nullable()->after('sekolah_id')->constrained('dana_bos')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dana_bos', function (Blueprint $table) {
            $table->dropColumn(['no_invoice', 'jenjang']);
        });

        Schema::table('print_histories', function (Blueprint $table) {
            $table->dropForeign(['dana_bos_id']);
            $table->dropColumn('dana_bos_id');
        });
    }
};
