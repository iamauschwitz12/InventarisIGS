<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruang extends Model
{
    protected $guarded = [];
    protected $fillable = ['ruang', 'lantai_id', 'gedung_id'];

    public function lantai()
    {
        return $this->belongsTo(Lantai::class);
    }

    public function gedung()
    {
        return $this->belongsTo(Gedung::class);
    }
}
