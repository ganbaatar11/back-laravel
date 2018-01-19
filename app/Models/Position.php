<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    //
    protected $table = 'positions';
    public $primaryKey  = 'pos_id';
    protected $hidden = ['created_at','updated_at'];
}
