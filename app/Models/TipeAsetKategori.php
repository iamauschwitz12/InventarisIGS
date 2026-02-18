<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipeAsetKategori extends Model
{
    protected $table = "tipe_aset_kategoris";

    protected $fillable = [
        'tipe_aset_id',
        'nama_tipe_kategori',
    ];

    public function tipeAset()
    {
        return $this->belongsTo(TipeAset::class);
    }
}
