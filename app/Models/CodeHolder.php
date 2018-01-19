<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CodeHolder extends Model
{
    protected $table = 'y_code_holders';
    public $primaryKey  = 'ych_id';

    protected $hidden = ['created_at','updated_at'];
    
    public function matches() {
		return $this->hasMany('App\Models\Match','mtc_y_code','ych_code');
	}

	public function contests() {
		return $this->hasMany('App\Models\Contest','con_ych_code','ych_code');
	}

	public function scopeA($query,$id){
		return $query->where("ych_id",$id)->first();
	}

	public function scopeLiveOrUpcoming($query){
		return $query->where("ych_state","live")
					->orWhere("ych_state","upcoming")
					->get();
	}

	public function scopeUpcoming($query){
		return $query->where("ych_state","upcoming")->get();
	}

	public function scopeLive($query){
		return $query->where("ych_state","live")->get();
	}

	public function updateState($state){
		$this->state = $state;
		$this->save();
	}

	public function updateStateWithContest($state){
		$this->ych_state = $state;
		print_r("before save\n");
		$this->save();
		print_r("after save\n");
		$contests = $this->contests;
		foreach ($contests as $contest) {
            $contest->updateState($state);
        }
	}
}
