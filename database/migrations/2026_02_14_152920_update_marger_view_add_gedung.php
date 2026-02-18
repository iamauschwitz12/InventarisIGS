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
        \Illuminate\Support\Facades\DB::statement("
            CREATE OR REPLACE VIEW marger_view AS
            SELECT 
                CONCAT('P-', p.id) as unique_id,
                p.nama_barang,
                r.ruang as ruang,
                l.lantai as lantai,
                g.nama_gedung as gedung,
                p.jumlah,
                'Pribadi' as sumber,
                p.tipe_aset_id,
                t.tipe_aset
            FROM pribadis p
            LEFT JOIN ruangs r ON p.ruang_id = r.id
            LEFT JOIN lantais l ON p.lantai_id = l.id
            LEFT JOIN gedungs g ON p.gedung_id = g.id
            LEFT JOIN tipe_asets t ON p.tipe_aset_id = t.id

            UNION ALL

            SELECT 
                CONCAT('S-', s.id) as unique_id,
                s.nama_barang,
                r.ruang as ruang,
                l.lantai as lantai,
                g.nama_gedung as gedung,
                s.jumlah,
                'Sekolah' as sumber,
                s.tipe_aset_id,
                t.tipe_aset
            FROM sekolahs s
            LEFT JOIN ruangs r ON s.ruang_id = r.id
            LEFT JOIN lantais l ON s.lantai_id = l.id
            LEFT JOIN gedungs g ON s.gedung_id = g.id
            LEFT JOIN tipe_asets t ON s.tipe_aset_id = t.id

            UNION ALL

            SELECT 
                CONCAT('D-', d.id) as unique_id,
                d.nama_barang,
                r.ruang as ruang,
                l.lantai as lantai,
                g.nama_gedung as gedung,
                d.jumlah,
                'Dana BOS' as sumber,
                d.tipe_aset_id,
                t.tipe_aset
            FROM dana_bos d
            LEFT JOIN ruangs r ON d.ruang_id = r.id
            LEFT JOIN lantais l ON d.lantai_id = l.id
            LEFT JOIN gedungs g ON d.gedung_id = g.id
            LEFT JOIN tipe_asets t ON d.tipe_aset_id = t.id
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("DROP VIEW IF EXISTS marger_view");
    }
};
