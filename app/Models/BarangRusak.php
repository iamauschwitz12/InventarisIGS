<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangRusak extends Model
{
    protected $fillable = [
        'inventaris_type',
        'gedung_id',
        'lantai_id',
        'ruang_id',
        'tipe_aset_id',
        'inventaris_id',
        'kode_inventaris',
        'jumlah_rusak',
        'tgl_rusak',
        'tgl_input',
        'keterangan',
        'no_seri',
    ];

    protected $casts = [
        'kode_inventaris' => 'array',
        'inventaris_id' => 'array',
        'tgl_rusak' => 'date',
        'tgl_input' => 'date',
    ];

    public function gedung()
    {
        return $this->belongsTo(Gedung::class);
    }

    /**
     * Get the first source inventory item.
     */
    public function getInventarisAttribute()
    {
        $ids = $this->inventaris_id;
        if (empty($ids))
            return null;

        $firstId = is_array($ids) ? $ids[0] : $ids;
        $model = $this->inventaris_type;
        if (!$model)
            return null;

        return $model::find($firstId);
    }

    /**
     * Get all source inventory items.
     */
    public function getInventarisItemsAttribute()
    {
        $ids = $this->inventaris_id;
        if (empty($ids))
            return collect();

        $ids = is_array($ids) ? $ids : [$ids];
        $model = $this->inventaris_type;
        if (!$model)
            return collect();

        return $model::whereIn('id', $ids)->get();
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

    // Relasi dinamis untuk menampilkan tipe aset sesuai jenis inventaris
    public function getTipeAsetDisplay()
    {
        if ($this->inventaris_type === DanaBos::class && $this->inventaris) {
            return $this->inventaris->tipeAsetDanaBos->tipe_aset_dana_bos ?? '-';
        }

        return $this->tipeAset->tipe_aset ?? '-';
    }

    public function engineering()
    {
        return $this->hasOne(Engineering::class);
    }
}