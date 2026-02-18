<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferInventaris extends Model
{
    protected $fillable = [
        'jenis_inventaris',
        'sumber_id',
        'gedung_asal_id',
        'lantai_asal_id',
        'ruang_asal_id',
        'gedung_tujuan_id',
        'lantai_tujuan_id',
        'ruang_tujuan_id',
        'nama_barang',
        'kode_inventaris',
        'jumlah_transfer',
        'tanggal_transfer',
        'keterangan',
        'user_id',
    ];

    protected $casts = [
        'tanggal_transfer' => 'date',
        'kode_inventaris' => 'array',
        'sumber_id' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gedungAsal()
    {
        return $this->belongsTo(Gedung::class, 'gedung_asal_id');
    }

    public function lantaiAsal()
    {
        return $this->belongsTo(Lantai::class, 'lantai_asal_id');
    }

    public function ruangAsal()
    {
        return $this->belongsTo(Ruang::class, 'ruang_asal_id');
    }

    public function gedungTujuan()
    {
        return $this->belongsTo(Gedung::class, 'gedung_tujuan_id');
    }

    public function lantaiTujuan()
    {
        return $this->belongsTo(Lantai::class, 'lantai_tujuan_id');
    }

    public function ruangTujuan()
    {
        return $this->belongsTo(Ruang::class, 'ruang_tujuan_id');
    }

    /**
     * Get the source model instance.
     */
    public function getSumberModelAttribute()
    {
        return match ($this->jenis_inventaris) {
            'pribadi' => Pribadi::find($this->sumber_id),
            'sekolah' => Sekolah::find($this->sumber_id),
            'dana_bos' => DanaBos::find($this->sumber_id),
            default => null,
        };
    }
}
