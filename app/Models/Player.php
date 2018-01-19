<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    //
    protected $table = 'players';
    public $primaryKey  = 'player_id';
    protected $hidden = ['created_at','updated_at'];

    public function scopeA($query,$player_code){
		return $query->where('player_code',$player_code)->first();
	}

    public function team(){
	    return $this->belongsTo('App\Models\Team', 'player_team_id', 'team_id');
	}
}
