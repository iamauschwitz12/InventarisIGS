<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Engineering extends Model
{
    protected $table = 'engineering';

    protected $fillable = [
        'barang_rusak_id',
        'status',
        'keterangan',
        'tgl_mulai_perbaikan',
        'tgl_selesai_perbaikan',
    ];

    protected $casts = [
        'tgl_mulai_perbaikan' => 'date',
        'tgl_selesai_perbaikan' => 'date',
    ];

    public function barangRusak()
    {
        return $this->belongsTo(BarangRusak::class);
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'sedang_diperiksa' => 'Sedang di Periksa',
            'menunggu_antrian' => 'Menunggu Antrian',
            'sedang_diperbaiki' => 'Sedang di Perbaiki',
            'telah_diperbaiki' => 'Telah di Perbaiki',
            default => '-',
        };
    }
}
