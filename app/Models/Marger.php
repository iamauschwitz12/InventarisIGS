<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marger extends Model
{
   protected $table = 'marger_view';

    protected $primaryKey = 'unique_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;
}
