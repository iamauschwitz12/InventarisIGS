<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pribadis', function (Blueprint $table) {
            $table->string('group_id')->nullable()->after('kode_inventaris');
            $table->index('group_id');
        });

        Schema::table('sekolahs', function (Blueprint $table) {
            $table->string('group_id')->nullable()->after('kode_inventaris');
            $table->index('group_id');
        });
    }

    public function down(): void
    {
        Schema::table('pribadis', function (Blueprint $table) {
            $table->dropIndex(['group_id']);
            $table->dropColumn('group_id');
        });

        Schema::table('sekolahs', function (Blueprint $table) {
            $table->dropIndex(['group_id']);
            $table->dropColumn('group_id');
        });
    }
};
