<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NbaPlayerStat extends Model
{
    //
    protected $table = 'nba_player_stats';
    public $primaryKey  = 'nps_id';
    protected $hidden = ['created_at','updated_at'];
    public function game(){
        return $this->belongsTo('App\Models\Match', 'nps_game_code', 'mtc_code');
    }

    public function scopeStats($query,$game_code,$player_code){
        return $query->where('nps_game_code',$game_code)
                    ->where('nps_player_code',$player_code)
                    ->firstOrFail();
    }

    public function scopeGetState($query,$game_code,$player_code){
        return $query->where('nps_game_code',$game_code)->where('nps_player_code', $player_code)->first();
    }

    public function scopeGameLog($query,$player_code){
        return $query->where('nps_player_code',$player_code)->get();
    }
}
