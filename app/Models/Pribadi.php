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
        'no_seri',
        'gedung_id',
        'no_invoice',
        'tipe_aset_kategori_id',
        'kode_inventaris',
        'jenjang',
        'group_id',
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
    public function gedung()
    {
        return $this->belongsTo(Gedung::class);
    }
    public function tipeAsetKategori()
    {
        return $this->belongsTo(TipeAsetKategori::class, 'tipe_aset_kategori_id');
    }

    public function printHistories()
    {
        return $this->hasMany(PrintHistory::class);
    }
}
