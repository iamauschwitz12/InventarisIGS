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
        Schema::table('ruangs', function (Blueprint $table) {
            Schema::table('ruangs', function (Blueprint $table) {
                if (! Schema::hasColumn('ruangs', 'lantai_id')) {
                    $table->unsignedBigInteger('lantai_id')->nullable()->after('ruang');
                }
            });

            DB::table('ruangs')->where('ruang', 'like', '4%')->update(['lantai_id' => 3]);
            DB::table('ruangs')->where('ruang', 'like', '5%')->update(['lantai_id' => 2]);
            DB::table('ruangs')->where('ruang', 'like', '6%')->update(['lantai_id' => 1]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ruangs', function (Blueprint $table) {
            $table->dropColumn('lantai_id');
        });
    }
};
