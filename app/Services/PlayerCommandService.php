<?php

namespace App\Services;

use Log;

use \GuzzleHttp\Client;

use App\Models\CodeHolder;
use App\Models\Match;
use App\Models\Contest;
use App\Models\Team;
use App\Models\Player;

use App\Services\NbaCallFunctionService;

class PlayerCommandService
{
	static $arrayTeam = [
		 "nba.t.27" => "https://s.yimg.com/xe/ipt/was_nonWhite.png"
		,"nba.t.4"  => "http://l.yimg.com/iu/api/res/1.2/vVhTdZPThBrHT5j4RpBE2A--/YXBwaWQ9c2hhcmVkO2ZpPWZpbGw7aD01MDA7cT0xMDA7dz01MDA-/http://l.yimg.com/xe/i/us/sp/v/nba/teams/20131107/84/500x500/chi.gif"
		,"nba.t.14" => "http://l.yimg.com/iu/api/res/1.2/mxPtSGk.EsMxgETX2QB9Kw--/YXBwaWQ9c2hhcmVkO2ZpPWZpbGw7aD01MDA7cT0xMDA7dz01MDA-/http://l.yimg.com/xe/i/us/sp/v/nba/teams/20131107/84/500x500/mia.gif"
		,"nba.t.9"  => "http://l.yimg.com/iu/api/res/1.2/TJQjJIo_U5OQ8QRTmOWbKQ--/YXBwaWQ9c2hhcmVkO2ZpPWZpbGw7aD01MDA7cT0xMDA7dz01MDA-/http://l.yimg.com/xe/i/us/sp/v/nba/teams/20131107/84/500x500/gsw.gif"
		,"nba.t.24" => "http://l.yimg.com/iu/api/res/1.2/wJ_i05upk_pbjo5KzW_XlQ--/YXBwaWQ9c2hhcmVkO2ZpPWZpbGw7aD01MDA7cT0xMDA7dz01MDA-/http://l.yimg.com/xe/i/us/sp/v/nba/teams/20131107/84/500x500/sas.gif"
		,"nba.t.17" => "http://l.yimg.com/iu/api/res/1.2/IxstD9pBoPYYhMYGuy9CbQ--/YXBwaWQ9c2hhcmVkO2ZpPWZpbGw7aD01MDA7cT0xMDA7dz01MDA-/http://l.yimg.com/xe/i/us/sp/v/nba/teams/20131107/84/500x500/bro.gif"
		,"nba.t.25" => "http://l.yimg.com/iu/api/res/1.2/4lW4OZXjy3de9E6voxvFAA--/YXBwaWQ9c2hhcmVkO2ZpPWZpbGw7aD01MDA7cT0xMDA7dz01MDA-/http://l.yimg.com/xe/i/us/sp/v/nba/teams/20131107/84/500x500/okc.gif"
		,"nba.t.16" => "http://l.yimg.com/iu/api/res/1.2/lGsjmuD4BRmTDQUNwkvOrw--/YXBwaWQ9c2hhcmVkO2ZpPWZpbGw7aD01MDA7cT0xMDA7dz01MDA-/http://l.yimg.com/xe/i/us/sp/v/nba/teams/20131107/84/500x500/min.gif"
		,"nba.t.15" => "http://l.yimg.com/iu/api/res/1.2/jXAIJLMBNexleOoFsBd87g--/YXBwaWQ9c2hhcmVkO2ZpPWZpbGw7aD01MDA7cT0xMDA7dz01MDA-/http://l.yimg.com/xe/i/us/sp/v/nba/teams/20131107/84/500x500/mil.gif"
		,"nba.t.10" => "http://l.yimg.com/iu/api/res/1.2/1MypUshJqq2BrVf_OgJ.jw--/YXBwaWQ9c2hhcmVkO2ZpPWZpbGw7aD01MDA7cT0xMDA7dz01MDA-/http://l.yimg.com/xe/i/us/sp/v/nba/teams/20131107/84/500x500/hou.gif"
		,"nba.t.29" => "http://l.yimg.com/iu/api/res/1.2/VnJ7QdvFKec1t8qTURAkpA--/YXBwaWQ9c2hhcmVkO2ZpPWZpbGw7aD01MDA7cT0xMDA7dz01MDA-/http://l.yimg.com/xe/i/us/sp/v/nba/teams/20131107/84/500x500/mem.gif"
		,"nba.t.3"  => "http://l.yimg.com/iu/api/res/1.2/W_foX6sI0TA07tW6sCX68Q--/YXBwaWQ9eXZpZGVvO2ZpPWZpbGw7aD01MDA7cT04MDt3PTUwMA--/http://l.yimg.com/dh/ap/nba/logos/new_orleans_pelicans.png"
		,"nba.t.13" => "http://l.yimg.com/iu/api/res/1.2/Z2_if4k6cPPAEa3ZveMBRQ--/YXBwaWQ9c2hhcmVkO2ZpPWZpbGw7aD01MDA7cT0xMDA7dz01MDA-/http://l.yimg.com/xe/i/us/sp/v/nba/teams/20131107/84/500x500/lal.gif"
		,"nba.t.21" => "http://l.yimg.com/xe/i/us/sp/v/nba/teams/20131113/84/500x500/pho_500x500.png"
		,"nba.t.11" => "http://l.yimg.com/iu/api/res/1.2/dfgzo_L9tJfxY94cOwGXtA--/YXBwaWQ9c2hhcmVkO2ZpPWZpbGw7aD01MDA7cT0xMDA7dz01MDA-/http://l.yimg.com/xe/i/us/sp/v/nba/teams/20131107/84/500x500/ind.gif"
		,"nba.t.8"  => "http://l.yimg.com/iu/api/res/1.2/XsDs2jUoRM_p_uW.vJShJw--/YXBwaWQ9c2hhcmVkO2ZpPWZpbGw7aD01MDA7cT0xMDA7dz01MDA-/http://l.yimg.com/xe/i/us/sp/v/nba/teams/20131107/84/500x500/det.gif"
		,"nba.t.2"  => "http://l.yimg.com/iu/api/res/1.2/P5KLOq_xppk8fSEXYrefWw--/YXBwaWQ9c2hhcmVkO2ZpPWZpbGw7aD01MDA7cT0xMDA7dz01MDA-/http://l.yimg.com/xe/i/us/sp/v/nba/teams/20131107/84/500x500/bos.gif"
		,"nba.t.18" => "http://l.yimg.com/iu/api/res/1.2/GgaDCy5BL8_Lgc5G7oLxww--/YXBwaWQ9c2hhcmVkO2ZpPWZpbGw7aD01MDA7cT0xMDA7dz01MDA-/http://l.yimg.com/xe/i/us/sp/v/nba/teams/20131107/84/500x500/nyk.gif"
		,"nba.t.5"  => "http://l.yimg.com/iu/api/res/1.2/s8CtrixuKK2R2oY_Dsrc1w--/YXBwaWQ9c2hhcmVkO2ZpPWZpbGw7aD01MDA7cT0xMDA7dz01MDA-/http://l.yimg.com/xe/i/us/sp/v/nba/teams/20131107/84/500x500/cle.gif"
		,"nba.t.6"  => "http://l.yimg.com/iu/api/res/1.2/pniwWrM.EJkWdqusmwmMNA--/YXBwaWQ9c2hhcmVkO2ZpPWZpbGw7aD01MDA7cT0xMDA7dz01MDA-/http://l.yimg.com/xe/i/us/sp/v/nba/teams/20131107/84/500x500/dal.gif"
		,"nba.t.23" => "http://l.yimg.com/iu/api/res/1.2/.lQGLtRuHBPedHNfobWPbg--/YXBwaWQ9c2hhcmVkO2ZpPWZpbGw7aD01MDA7cT0xMDA7dz01MDA-/http://l.yimg.com/xe/i/us/sp/v/nba/teams/20131107/84/500x500/sac.gif"
		,"nba.t.12" => "http://l.yimg.com/iu/api/res/1.2/Sh2evxiaHddrSSEk.s6QBg--/YXBwaWQ9c2hhcmVkO2ZpPWZpbGw7aD01MDA7cT0xMDA7dz01MDA-/http://l.yimg.com/xe/i/us/sp/v/nba/teams/20131107/84/500x500/lac.gif"
		,"nba.t.7"  => "http://l.yimg.com/iu/api/res/1.2/rxoMYVU2fDLKVFDG0GtoGw--/YXBwaWQ9c2hhcmVkO2ZpPWZpbGw7aD01MDA7cT0xMDA7dz01MDA-/http://l.yimg.com/xe/i/us/sp/v/nba/teams/20131107/84/500x500/den.gif"
		,"nba.t.1"  => "http://l.yimg.com/iu/api/res/1.2/8HIWpAr1J7UlGbQunXiyhQ--/YXBwaWQ9c2hhcmVkO2ZpPWZpbGw7aD01MDA7cT0xMDA7dz01MDA-/http://l.yimg.com/xe/i/us/sp/v/nba/teams/20131107/84/500x500/atl.gif"
		,"nba.t.30" => "http://l.yimg.com/iu/api/res/1.2/WtD246PSSsXoEZZQrU06lw--/YXBwaWQ9c2hhcmVkO2ZpPWZpbGw7aD01MDA7cT0xMDA7dz01MDA-/http://l.yimg.com/dh/ap/nba/logos/charlotte_hornets.png"
		,"nba.t.26" => "http://l.yimg.com/iu/api/res/1.2/9evOQ_RD0fotQoj1uaLnrQ--/YXBwaWQ9c2hhcmVkO2ZpPWZpbGw7aD01MDA7cT0xMDA7dz01MDA-/http://l.yimg.com/xe/i/us/sp/v/nba/teams/20131107/84/500x500/uth.gif"
		,"nba.t.22" => "http://l.yimg.com/iu/api/res/1.2/sgefqMEJ1HsmKZZNmruSIA--/YXBwaWQ9c2hhcmVkO2ZpPWZpbGw7aD01MDA7cT0xMDA7dz01MDA-/http://l.yimg.com/xe/i/us/sp/v/nba/teams/20131107/84/500x500/por.gif"
		,"nba.t.20" => "http://l.yimg.com/iu/api/res/1.2/xzHWUQ67A39XRtigJPBeGA--/YXBwaWQ9c2hhcmVkO2ZpPWZpbGw7aD01MDA7cT0xMDA7dz01MDA-/http://l.yimg.com/xe/i/us/sp/v/nba/teams/20131107/84/500x500/phi.gif"
		,"nba.t.28" => "http://l.yimg.com/iu/api/res/1.2/CZhasOYUMkgybJzb9fLhhg--/YXBwaWQ9c2hhcmVkO2ZpPWZpbGw7aD01MDA7cT0xMDA7dz01MDA-/http://l.yimg.com/xe/i/us/sp/v/nba/teams/20131107/84/500x500/tor.gif"
		,"nba.t.19" => "http://l.yimg.com/iu/api/res/1.2/nn73Ljg2n2GGqWD6kj8Kew--/YXBwaWQ9c2hhcmVkO2ZpPWZpbGw7aD01MDA7cT0xMDA7dz01MDA-/http://l.yimg.com/xe/i/us/sp/v/nba/teams/20131107/84/500x500/orl.gif"];
	
	public static function updateContest($yCode){
    	$client = new Client();
		$url = "https://dfyql-ro.sports.yahoo.com/v2/contestPlayers?contestId=".$yCode;
		$res = $client->request('GET', $url, []);
		$data = json_decode($res->getBody(), false);

		if(!$data){
			print_r("failed");
			// NbaCallFunctionService::execute("PlayerCommandService::updateContest contest_id: ".$yCode,"!data error");
		}

		$players = $data->players->result;

		foreach ($players as $player) {
			self::insertPlayer($player);
		}
		print_r("updated");
		// NbaCallFunctionService::execute("PlayerCommandService::updateContest contest_id: ".$yCode,"success");
	}

	private static function insertTeam($team){
		$insertedId = Team::where('team_code', '=', $team->code)->first();
		if ($insertedId) {
			return $insertedId->team_id;
		} else {
			try{
				$nbateam = new Team;
				$nbateam->team_code = $team->code;
				$nbateam->team_location = $team->location;
				$nbateam->team_name = $team->name;
				$nbateam->team_t_name = $team->teamName;
				$nbateam->abbr = $team->abbr;
				$nbateam->imageUrl = self::$arrayTeam[$team->code];
				$nbateam->save();
				$insertedId = $nbateam;
			}catch ( \Illuminate\Database\QueryException $e) {
                print_r($e->errorInfo);
            }
			
		}
		return $insertedId->team_id;
	}

	private static function insertPlayer($player){
		$insertedId = Player::where('player_code', '=', $player->code)->first();
		if ($insertedId) {
			$insertedId->player_code = $player->code;
			$insertedId->player_firstname = $player->firstName;
			$insertedId->player_lastname = $player->lastName;
			$insertedId->player_sport_code = $player->sportCode;
			$insertedId->player_number = $player->number;
			$insertedId->player_jersey_number = $player->jerseyNumber;
			$insertedId->player_status = $player->status;
			$insertedId->player_image_url = $player->imageUrl;
			$insertedId->player_large_image_url = $player->largeImageUrl;
			$insertedId->player_team_id = self::insertTeam($player->team);
			$insertedId->player_salary = $player->salary;
			$insertedId->player_original_salary = $player->originalSalary;
			$insertedId->player_projected_points = $player->projectedPoints;
			$insertedId->player_starting = $player->starting;
			$insertedId->player_primary_position = $player->primaryPosition;
			$insertedId->player_eligible_position = implode(", ",$player->eligiblePositions);
			$insertedId->player_fantasy_points_per_game = $player->fantasyPointsPerGame;
			$insertedId->save();	
		} else {
			$nba_player = new Player;
			$nba_player->player_code = $player->code;
			$nba_player->player_firstname = $player->firstName;
			$nba_player->player_lastname = $player->lastName;
			$nba_player->player_sport_code = $player->sportCode;
			$nba_player->player_number = $player->number;
			$nba_player->player_jersey_number = $player->jerseyNumber;
			$nba_player->player_status = $player->status;
			$nba_player->player_image_url = $player->imageUrl;
			$nba_player->player_large_image_url = $player->largeImageUrl;
			$nba_player->player_team_id = self::insertTeam($player->team);
			$nba_player->player_salary = $player->salary;
			$nba_player->player_original_salary = $player->originalSalary;
			$nba_player->player_projected_points = $player->projectedPoints;
			$nba_player->player_starting = $player->starting;
			$nba_player->player_primary_position = $player->primaryPosition;
			$nba_player->player_eligible_position = implode(", ",$player->eligiblePositions);
			$nba_player->player_fantasy_points_per_game = $player->fantasyPointsPerGame;
			$nba_player->save();	
		}
	}
}
