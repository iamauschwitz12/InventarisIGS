<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class Pribadi extends Model
{
    protected $fillable = [
        'nama_barang',
        'lantai_id',
        'ruang_id',
        'tipe_aset_id',
        'harga',
        'tgl_beli',
        'jumlah',
        'img',
        'keterangan',
    ];

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
        return $this->belongsTo(TipeAset::class, 'tipe_aset_id');
    }
    public function setNamaBarangAttribute($value)
    {
        $this->attributes['nama_barang'] = strtoupper($value);
    }
    public function barangRusak()
    {
        return $this->morphMany(BarangRusak::class, 'inventaris');
    }
}
