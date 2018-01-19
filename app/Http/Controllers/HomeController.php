<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use \GuzzleHttp\Client;
use Response;
use DB;
use Carbon\Carbon;

use App\Models\Team;
use App\Models\Contest;
use App\Models\Match;
use App\Models\CodeHolder;
use App\Models\Entry;
use App\Models\Lineup;
use App\Models\NbaPlayerStat;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class HomeController extends BaseController
{

	public function __construct()
	{
		$this->middleware('jwt.auth', ['except' => ['holders','teams','playerGameLog','test']]);
	}

	function teams(){
		$teams = Team::all();
		if(!$teams){
			return $this->errorResponse('teams','teams');
		}
		return $this->successResponse('teams',$teams);
	}

	function holders(){
		$holders = CodeHolder::upcoming();
		foreach ($holders as $holder) {
			$matches = $holder->matches;
			foreach ($matches as $match) {
				$homeTeam = $match->homeTeam;
				$awayTeam = $match->awayTeam;
			}
		}
		if(!$holders){
			return $this->errorResponse('holders','holders');
		}
		return $this->successResponse('holders',$holders);
	}
	
	function test(){
        $client = new Client();
        $match_code = 'nba.g.1674788';
        $url = 'https://api-secure.sports.yahoo.com/v1/editorial/s/boxscore/'.$match_code;
        // $url = 'https://api-secure.sports.yahoo.com/v1/editorial/s/boxscore/nba.g.1674221';
        
        $res = $client->request('GET', $url, []);
        
        $boxscore = json_decode($res->getBody(), false)->service->boxscore;

        if (array_key_exists("player_stats", $boxscore)) {
            $player_stats = $boxscore->player_stats;
            foreach($player_stats as $key => $player_var_s){
                $player_stat = $player_var_s->{'nba.stat_variation.2'};

                $playerStats = new NbaPlayerStat;

                $tmp = NbaPlayerStat::getState($match_code,$key);
                if (isset($tmp->nps_id)){
                    $playerStats = $tmp;
                }
                self::loadPlayerState($playerStats,$key,$player_stat);

                echo $playerStats;
                echo "<br>";
            }
        }

	}

    private function loadPlayerState($playerStats,$key,$value){
        $playerStats->nps_min = $value->{"nba.stat_type.3"};
        $playerStats->nps_threept = explode("-",$value->{"nba.stat_type.30"})[0];
        $playerStats->nps_reb = $value->{"nba.stat_type.16"};
        $playerStats->nps_ast = $value->{"nba.stat_type.17"};
        $playerStats->nps_to = $value->{"nba.stat_type.20"};
        $playerStats->nps_st = $value->{"nba.stat_type.18"};
        $playerStats->nps_blk = $value->{"nba.stat_type.19"};
        $playerStats->nps_pt = $value->{"nba.stat_type.13"};
    }

    function playerGameLog(Request $request){
        $player_code = $request->player_code;
        if (!$player_code)
            return $this->errorResponse('game_log','player_code');
        $player_stats = NbaPlayerStat::gameLog($player_code);
        if (!$player_stats)
            return $this->errorResponse('game_log','player_stats');
        $pStats = array();
        foreach ($player_stats as $player_status){
            $tmpStatus = clone $player_status;
            $pStatus = $tmpStatus->game;
            $pStatus['stats'] = $player_status;
            $pStatus->homeTeam;
            $pStatus->awayTeam;
            array_push($pStats,$pStatus);
        }
        usort($pStats, function($a, $b) {
            return $a['mtc_start_time'] < $b['mtc_start_time'];
        });
        
        return $this->successResponse('game_log',$pStats);
    }
}
