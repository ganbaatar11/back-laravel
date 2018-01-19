<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\BaseController;

use App\Models\Contest;
use App\Models\Match;
use App\Models\Team;

use Response;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ContestController extends BaseController
{

	public function __construct(){
		$this->middleware('jwt.auth', ['except' => ['getContestList',
													'getContest',
													'getContestPlayers']]);
	}

	function getContestList(){

		$contests = Contest::upcomingContest()->get();
		if(!$contests){
			return $this->errorResponse("contests","contests");
		}
		return $this->successResponse("contests",$contests);
	}

	function getContest($contest_id){

		$contest = Contest::a($contest_id);

		if(!isset($contest->con_id)){
			return $this->errorResponse("contest","contest");
		}

		$matches = $contest->matches;

		if(!$matches){
			return $this->errorResponse("contest","matches");
		}

		foreach ($matches as $match) {
		    $match["home_team"] = $match->homeTeam;
		    $match["away_team"] = $match->awayTeam;
		}
		$contest->positions;
		$contest["matches"] = $matches;

		return $this->successResponse("contest",$contest);
	}
	// tested
	function createContest(Request $request){
		$msg = "";
		try {

			$user = JWTAuth::parseToken()->authenticate();
		
			$contest = new Contest;
			$contest->con_ych_code = $request->ych_code;
			$contest->con_create_user_id = $user->id;
			$contest->con_sport_code = $request->sport_code;
			$contest->con_title = $request->title;
			$contest->con_scope = $request->scope;
			$contest->con_entry_fee = $request->entry_fee;
			$contest->con_entry_limit = $request->entry_limit;
			$contest->con_total_prize = $request->total_prize;
			$contest->con_start_time = $request->start_time;

			$contest->con_type = "league";
			$contest->con_entry_count = 0;
			$contest->con_multiple_entry_limit = 1;
			$contest->con_salary_cap = 200;
			$contest->con_multiple_entry = 0;
			$contest->con_guaranteed = 0;
			$contest->con_state = "upcoming";
			$contest->save();
			$msg = $contest->con_id;

		} catch ( \Illuminate\Database\QueryException $e) {
			dd($e->errorInfo);
			return $this->errorResponse("result",$e->errorInfo);
		}

		return $this->successResponse("result",$msg);
	}

	function getContestPlayers($contest_id){
		$contest = Contest::a($contest_id);

		if (!isset($contest->con_id)){
			return $this->errorResponse("players","contest");
		}
		
		$matches = $contest->matches;

		if (!$matches){
			return $this->errorResponse("players","matches");
		}

		$players = array();
		foreach ($matches as $match) {
			$tmpMatch = clone $match;
			$homeTeam = $match->homeTeam;
			$awayTeam = $match->awayTeam;

			$homePlayers = $homeTeam->players;
			$awayPlayers = $awayTeam->players;

			$homeTmp = clone $tmpMatch;
			$homeTmp['opponent'] = "@".$homeTeam->abbr;

			$awayTmp = clone $tmpMatch;
			$awayTmp['opponent'] = "v".$awayTeam->abbr;

			foreach ($homePlayers as $homePlayer) {
				$homePlayer->team;
				$homePlayer['game'] = $awayTmp;
				array_push($players,$homePlayer);
			}

			foreach ($awayPlayers as $awayPlayer) {
				$awayPlayer->team;
				$awayPlayer['game'] = $homeTmp;
				array_push($players,$awayPlayer);
			}
			
		}
		return $this->successResponse("players",$players);
	}
	
}