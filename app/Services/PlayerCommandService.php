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
	static $arrayTeam = ["{team_id}" => "{image_link_map}"];
	
	public static function updateContest($yCode){
    	$client = new Client();
		$url = "https://{player-stat-link}/contestPlayers?contestId=".$yCode;
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
