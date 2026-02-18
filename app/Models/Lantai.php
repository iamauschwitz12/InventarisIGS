<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lantai extends Model
{
    protected $guarded = [];
    protected $fillable = ['lantai', 'gedung_id'];

    public function ruangs()
    {
        return $this->hasMany(Ruang::class);
    }

    public function gedung()
    {
        return $this->belongsTo(Gedung::class);
    }
}
