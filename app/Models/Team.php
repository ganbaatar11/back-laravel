<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    //
    protected $table = 'teams';
    public $primaryKey  = 'team_id';
    protected $hidden = ['created_at','updated_at'];

    public function players() {
		return $this->hasMany('App\Models\Player','player_team_id','team_id');
	}
}
