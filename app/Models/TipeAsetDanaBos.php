<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipeAsetDanaBos extends Model
{
    protected $table = 'tipe_aset_dana_bos';

    protected $fillable = [
        'tipe_aset_dana_bos',
    ];

    public function danaBos()
    {
        return $this->hasMany(DanaBos::class, 'tipe_aset_dana_bos_id');
    }
}
