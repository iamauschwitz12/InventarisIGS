<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangRusak extends Model
{
    protected $fillable = [
        'inventaris_type',
        'lantai_id',
        'ruang_id',
        'tipe_aset_id',
        'inventaris_id',
        'jumlah_rusak',
        'tgl_rusak',
        'tgl_input',
        'keterangan',
    ];

    public function inventaris()
    {
        return $this->morphTo();
    }

    // Relations to get details from ruangs, lantais, and tipe_asets through inventaris
    public function ruang()
    {
        return $this->belongsTo(Ruang::class);
    }

    public function lantai()
    {
        return $this->belongsTo(Lantai::class);
    }

    public function tipeAset()
    {
        return $this->belongsTo(TipeAset::class);
    }
}
