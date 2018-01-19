<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlayerDailySalary extends Model
{
    //
    protected $table = 'nba_contest';
    public $primaryKey  = 'pds_id';
    protected $hidden = ['created_at','updated_at'];
}
