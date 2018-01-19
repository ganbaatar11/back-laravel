<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Contest extends Model
{
    protected $table = 'contests';
    public $primaryKey  = 'con_id';
    protected $hidden = ['created_at','updated_at'];
    public function scopeA($query,$contest_id){
		return $query->where('con_id',$contest_id)->first();
	}

	public function matches(){
		return $this->hasMany('App\Models\Match', 'mtc_y_code', 'con_ych_code');
	}
	public function positions(){
		return $this->hasMany('App\Models\Position', 'pos_sport_code', 'con_sport_code');
	}

	
    public function scopeUpcomingContest($query){
		return $query->where("con_state","upcoming")->whereRaw("con_entry_count < con_entry_limit");
	}
	

	public function entries(){
	    return $this->hasMany('App\Models\Entry', 'ent_con_id', 'con_id');
	}

	public function scopeUserEntries($query,$state,$user_id){
		return $query->join('entries', 'con_id', '=', 'ent_con_id')
					->where("con_state",$state)
					->where('ent_user_id',$user_id)
					->get();
	}

	public function updateState($state){
		$this->con_state = $state;
		$this->save();
	}
}
