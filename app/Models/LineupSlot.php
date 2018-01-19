<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LineupSlot extends Model
{
    //
    protected $table = 'lineup_slots';
    public $primaryKey  = 'lus_id';
    protected $hidden = ['created_at','updated_at'];
    public function player(){
	    return $this->hasOne('App\Models\Player', 'player_code', 'lus_player_code');
	}
}
