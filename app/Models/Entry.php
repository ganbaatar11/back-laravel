<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    protected $table = 'entries';
    public $primaryKey  = 'ent_id';
    protected $hidden = ['created_at','updated_at'];
    public function entryUser(){
	    return $this->hasOne('App\Models\User', 'id', 'ent_user_id');
	}

	public function entryLineups(){
	    return $this->hasMany('App\Models\Lineup', 'lu_id', 'ent_lu_id');
	}

	public function scopeA($query,$entry_id){
		return $query->where('ent_id',$entry_id)->first();
	}

	public function scopeEntriesOfContest($query,$contest_id){
		return $query->where("contest_id",$contest_id);
	}
}
