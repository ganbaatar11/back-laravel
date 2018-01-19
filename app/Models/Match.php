<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    protected $table = 'matches';
    public $primaryKey  = 'mtc_id';
    protected $hidden = ['created_at','updated_at'];
    public function homeTeam() {
		return $this->hasOne('App\Models\Team','team_code','mtc_home_team_code');
	}

	public function awayTeam() {
		return $this->hasOne('App\Models\Team','team_code','mtc_away_team_code');
	}

	public function scopeGame($query,$contest_code,$team_code){
		return $query->where("mtc_y_code",$contest_code)
					->where(function ($query) use ($team_code){
						$query->where('mtc_home_team_code',$team_code)
							->orWhere('mtc_away_team_code',$team_code);
					})->first();
	}

	public function scopeMatch($query,$contest_code,$match_code){
		return $query->where("mtc_y_code",$contest_code)
					->where("mtc_code",$match_code)
					->first();
	}
}
