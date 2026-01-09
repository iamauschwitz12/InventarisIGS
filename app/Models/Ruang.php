<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruang extends Model
{
    protected $guarded = [];
    protected $fillable = ['ruang','lantai_id'];

    public function lantai()
    {
        return $this->belongsTo(Lantai::class);
    }
}
