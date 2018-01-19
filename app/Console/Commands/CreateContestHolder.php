<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;

use App\Services\PlayerCommandService;


use App\Models\CodeHolder;
use App\Models\Match;
use Response;
use \GuzzleHttp\Client;

class CreateContestHolder extends Command
{
    protected $signature = 'crsh:create_holder';

    protected $description = 'Create yCode';

    public function handle ()
    {
        $this->turbaijee();
    }

    private function initMathes($contestId){
		$client = new Client();
		$url = 'https://dfyql-ro.sports.yahoo.com/v2/contest/'.$contestId;
		$res = $client->request('GET', $url, []);
		$data = json_decode($res->getBody(), false);
		$matches = $data ->contests->result[0]->series->games;
		// dd($matches);
        try {
    		foreach ($matches as $key => $value) {
    			$match = new Match;
    			$match->mtc_code = $value->code;
    			$match->mtc_y_code = $contestId;
    			$match->mtc_sport_code = $value->sportCode;
    			$match->mtc_home_team_code = $value->homeTeamCode;
    			$match->mtc_away_team_code = $value->awayTeamCode;
    			$match->mtc_status_type = $value->statusType;
    			$match->mtc_status = $value->status;
    			$match->mtc_lineup_available = $value->lineupAvailable;
    			$match->mtc_start_time = date('Y-m-d H:i:s T',$value->startTime/1000);
    			$match->mtc_location = $value->location;
    			$match->mtc_boxscore_link = $value->boxscoreLink;
    			$match->save();
    		}
        } catch ( \Illuminate\Database\QueryException $e) {
            // print_r($e->errorInfo);
        }
		return count($matches);
	}

    public function turbaijee(){
        $client = new Client();
        $url = 'https://dfyql-ro.sports.yahoo.com/v2/contests?sport=nba';
        //https://dfyql-ro.sports.yahoo.com/v2/contestsFilteredWeb?lang=en-US&sport=nba&entryFeeMin=0&entryFeeMax=0&sortAsc=false
        $res = $client->request('GET', $url, []);
        $data = json_decode($res->getBody(), false);
        if(!$data){
            return Response::json([
                'error' => [
                'message' => 'Data does not exist'
                ]
                ], 404);
        }
        $arr = [];
        $results = $data->contests->result;
        //Zuvhun free contest-s shuult hiij bga , busad ni cancel hiigdeh magadlal undurtei uchraas
        foreach($results as $key => $value) {
            if (preg_match('/(free)/i',$value->title,$res1) && count($res1) > 0){
                $arr[$value->startTime] = $value->id;
            }
        }

        $error = array();
        $msg = array();

        $maxHolder = new CodeHolder;
        $tmpCount = 0;
        foreach ($arr as $key => $value) {
            try {
                $codeHolder = new CodeHolder;
                $codeHolder->ych_code = $value;
                $codeHolder->ych_start_time = date('Y-m-d H:i:s T',$key/1000);
                $codeHolder->ych_state = 'upcoming';

                $matchSize = $this->initMathes($value);

                if ($matchSize > $tmpCount){
                    $maxHolder = $codeHolder;
                    $tmpCount = $matchSize;
                }

                $codeHolder->save();
                array_push($msg, $codeHolder);

            } catch ( \Illuminate\Database\QueryException $e) {
                array_push($error, $e->errorInfo);
            }
        }
        
        
        $allDailyMatches = $this->allMatches($maxHolder);
        return Response::json(['error' => $error,'msg' => $msg,'allDailyMatches' => $allDailyMatches] ,200);        
    }

    public function allMatches($mHolder){
        echo ($mHolder->ych_code);
        PlayerCommandService::updateContest($mHolder->ych_code);
        $matches = $mHolder->matches;
        return $matches;
    }
}