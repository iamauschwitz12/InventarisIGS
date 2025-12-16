<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipeAset extends Model
{
    protected $guarded = [];
    protected $fillable = ['tipe_aset'];

    public function pribadis()
    {
        return $this->hasMany(\App\Models\Pribadi::class, 'tipe_aset_id');
    }

    public function sekolahs()
    {
        return $this->hasMany(\App\Models\Sekolah::class, 'tipe_aset_id');
    }

    public function danaBos()
    {
        return $this->hasMany(\App\Models\DanaBos::class, 'tipe_aset_id');
    }
}
