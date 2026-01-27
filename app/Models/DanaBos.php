<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanaBos extends Model
{
    protected $fillable = [
        'nama_barang',
        'lantai_id',
        'ruang_id',
        'tipe_aset_dana_bos_id',
        'harga',
        'tgl_beli',
        'jumlah',
        'img',
        'keterangan',
        'no_seri',
    ];

    public function ruang()
    {
        return $this->belongsTo(Ruang::class);
    }

    public function lantai()
    {
        return $this->belongsTo(Lantai::class);
    }

    public function tipeAsetDanaBos()
    {
        return $this->belongsTo(TipeAsetDanaBos::class);
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
