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
        Schema::table('print_histories', function (Blueprint $table) {
            $table->integer('start_number')->nullable()->after('pribadi_id');
            $table->integer('jumlah')->nullable()->after('start_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('print_histories', function (Blueprint $table) {
            $table->dropColumn(['start_number', 'jumlah']);
        });
    }
};
