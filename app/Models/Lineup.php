<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lineup extends Model
{
    //
    protected $table = 'lineups';
    public $primaryKey  = 'lu_id';
    protected $hidden = ['created_at','updated_at'];
    public function lineupSlots(){
	    return $this->hasMany('App\Models\LineupSlot', 'lus_lu_id', 'lu_id');
	}

	public function contest(){
	    return $this->hasOne('App\Models\Contest', 'con_id', 'lu_con_id');
	}

}
