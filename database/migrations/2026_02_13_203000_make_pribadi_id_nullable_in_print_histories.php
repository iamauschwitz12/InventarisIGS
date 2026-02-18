<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Make pribadi_id nullable since print_histories can now belong to either pribadi or sekolah
     */
    public function up(): void
    {
        Schema::table('print_histories', function (Blueprint $table) {
            // Drop the foreign key first, then make nullable
            $table->dropForeign(['pribadi_id']);
            $table->foreignId('pribadi_id')->nullable()->change();
            $table->foreign('pribadi_id')->references('id')->on('pribadis')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('print_histories', function (Blueprint $table) {
            $table->dropForeign(['pribadi_id']);
            $table->foreignId('pribadi_id')->nullable(false)->change();
            $table->foreign('pribadi_id')->references('id')->on('pribadis')->cascadeOnDelete();
        });
    }
};
