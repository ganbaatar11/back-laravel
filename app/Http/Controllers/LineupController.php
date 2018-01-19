<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\BaseController;

use App\Models\Entry;
use App\Models\Contest;
use App\Models\Match;
use App\Models\NbaPlayerStat;
use App\Models\Lineup;
use App\Models\LineupSlot;
use App\Models\Player;

use Response;

use DB;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class LineupController extends BaseController
{

	public function __construct()
	{
		$this->middleware('jwt.auth', ['except' => []]);
	}

	function getLineup(Request $request){
		$entry_id = $request->entry_id;

		$entryLineup = $this->lineUp($entry_id);
		if(!$entryLineup){
			return $this->errorResponse('entry_lineup','entry_lineup');
		}
		return $this->successResponse("entry_lineup",$entryLineup);
	}

	function getUserLineUps(Request $request){
		
		$user = JWTAuth::parseToken()->authenticate();
        $user_id = $user->id;
		$state = $request->state;
		
		$entries = Contest::userEntries($state,$user_id);

		$lineups = array();
		foreach ($entries as $entry) {
			array_push($lineups,$this->lineUp($entry->ent_id));
		}

		if(!$lineups){
			return $this->errorResponse('lineups','lineups');
		}
		return $this->successResponse("lineups",$lineups);
	}

	function createContestEntry(Request $request){
		// request catch injection
		$contest = Contest::a($request->contest_id);
		$entries = $contest->entries;

		if ($contest->con_entry_limit > $contest->con_entry_count && count($entries) < $contest->con_entry_limit){

			DB::beginTransaction();

			try {
				$lineup = new Lineup;
				$lineup->lu_con_id = $contest->con_id;
				$lineup->lu_sport_code = "nba";
				$lineup->save();
			} 
			catch(\Exception $e){
				DB::rollback();
				throw $e;
			}

			try {
				$entry = new Entry;
				$user = JWTAuth::parseToken()->authenticate();
				$entry->ent_con_id = $request->contest_id;
				$entry->ent_lu_id = $lineup->lu_id;
				$entry->ent_user_id = $user->id;
				$entry->ent_score = 0;				//$request->score;
				$entry->ent_winnings = 0;			//$request->winnings;
				// $entry->canceledAt = 			//$request->canceledAt;
				$entry->ent_cancelable = 0;			//$request->cancelable;
				$entry->ent_maximum_points = 0;		//$request->maximumPoints;
				$entry->ent_profitable_points = 0;	//$request->profitablePoints;
				$entry->ent_minimum_points = 0;		//$request->minimumPoints;
				$entry->ent_projected_points = 0;		//$request->projectedPoints;
				$entry->ent_periods_remaining = 0;	//$request->periodsRemaining;
				$entry->ent_remaining_time_unit = 0;	//$request->remainingTimeUnit;
				$entry->ent_total_time_unit = 340;		//$request->totalTimeUnit;

				$entry->save();
			} 
			catch(\Exception $e)
			{
				DB::rollback();
				throw $e;
			}
			try {

				$players = json_decode($request->players);
				$players_cap = 0;
				foreach ($players as $player) {
					$tmp_player = Player::a($player->id);
					$players_cap += $tmp_player->player_salary;
				}

				if ($players_cap > $contest->con_salary_cap){
					DB::rollback();
					return $this->errorResponse('contest_lineup','salary capacity');
				}

				foreach ($players as $player) {
					$lineupSlot = new LineupSlot;
					$lineupSlot->lus_lu_id = $lineup->lu_id;
					$lineupSlot->lus_player_code = $player->id;
					$lineupSlot->lus_pos_id = $player->pos;
					$lineupSlot->save();
				}
				
			}
			catch(\Exception $e)
			{
				DB::rollback();
				return $this->errorResponse('contest_lineup','set NbaContestLineup');
			}

			// contest player count
			try{
				$contest->con_entry_count = $contest->con_entry_count + 1;
				$contest->save();
			}catch(\Exception $e)
			{
				DB::rollback();
				throw $e;
			}
			DB::commit();
		}else{
			return $this->errorResponse('contest_lineup','full contest');
		}


		if(!$entry){
			return $this->errorResponse('contest_lineup','contest_lineup');
		}
		return $this->successResponse("contest_lineup",$entry->ent_id);
	}

	function getLineupUsers(Request $request){

		$contest_id = $request->contest_id;
		if (!$contest_id){
			return $this->errorResponse('result','contest_id');
		}

		$contest = Contest::a($contest_id);
		$entries = $contest->entries;
		if (!$contest || !$entries){
			return $this->errorResponse("result","contest or entries");
		}

		foreach ($entries as $entry) {
			$entry['rank'] = 1;
			$entry->entryUser;
			
			$tmpEntry = clone $entry;
			$lineups = $tmpEntry->entryLineups;

			$player_score = 0.0;

			foreach ($lineups as $lineup) {
				$lineupSlots = $lineup->lineupSlots;
				$contest = $lineup->contest;
				foreach ($lineupSlots as $lineupSlot) {
					$player = $lineupSlot->player;
					$player_score += $this->getPlayerStats($player,$contest);
				}
			}

			$entry["score"] = $player_score;
		}
		$entries = $entries->toArray();
		usort($entries, function($a, $b) {
		    return $a['score'] < $b['score'];
		});
		$rank = 1;
		for ($rank = 0; $rank < count($entries); $rank++){
			$entries[$rank]['rank'] = $rank+1;
		}

		return $this->successResponse("result",$entries);
	}

	// correct
	private function lineUp($entry_id){
		$entry = Entry::a($entry_id);

		if(!isset($entry->ent_id))
			return null;

		$lineups = $entry->entryLineups;

		foreach ($lineups as $lineup) {
			$player_score = 0.0;
			$lineupSlots = $lineup->lineupSlots;
			$contest = $lineup->contest;
			foreach ($lineupSlots as $lineupSlot) {
				$player = $lineupSlot->player;
				$player_score += $this->getPlayerStats($player,$contest);
			}

			$lineup['user_points'] = $entry_id;
			$lineup['entry_id'] = $entry_id;
			// $lineup['contest'] = $contest;
		}
		return $lineups;
	}

	// correct
	private function getPlayerStats($player,$contest){
		try{
			$team = $player->team;
			$player["game"] = Match::game($contest->con_ych_code,$team->team_code);
			$tmpGame = clone $player["game"];
			$homeTeam = $tmpGame->homeTeam;
			$awayTeam = $tmpGame->awayTeam;
			
			if ($team->abbr == $homeTeam->abbr){
				$player["game"]['opponent'] = '@'.$awayTeam->abbr;
			}else{
				$player["game"]['opponent'] = 'v'.$homeTeam->abbr;
			}

			$player["stats"] = NbaPlayerStat::stats($player["game"]->mtc_code,$player->player_code);
			return $player["stats"]->nps_fpts;
		}catch(\Exception $e){
			$player["stats"] = null;
			return 0;
		}
	}
	
}