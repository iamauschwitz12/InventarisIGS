<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lantai extends Model
{
    protected $guarded = [];
    protected $fillable = ['lantai'];

    public function ruangs()
    {
        return $this->hasMany(Ruang::class);
    }
}
