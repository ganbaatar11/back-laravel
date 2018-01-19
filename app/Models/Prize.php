<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prize extends Model
{
    //
    protected $table = 'prizes';
    public $primaryKey  = 'prize_id';
    protected $hidden = ['created_at','updated_at'];
}
