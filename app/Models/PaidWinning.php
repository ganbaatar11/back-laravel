<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaidWinning extends Model
{
    //
    protected $table = 'paid_winnings';
    public $primaryKey  = 'pw_id';
    protected $hidden = ['created_at','updated_at'];
}
