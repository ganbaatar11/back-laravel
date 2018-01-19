<?php

namespace App\Services;

use Log;

use \GuzzleHttp\Client;

use App\Models\CodeHolder;
use App\Models\Match;
use App\Models\Contest;
use App\Models\Team;
use App\Models\NbaPlayerStat;

use App\Services\NbaCallFunctionService;
class MainCommandService
{

    public static function statusUpcomingToLive(){
        $codeHolders = CodeHolder::upcoming();
        
        foreach ($codeHolders as $codeHolder){
            if ($codeHolder->ych_start_time < date('Y-m-d H:i:s')){
                print_r($codeHolder->ych_id."\n");
                $codeHolder->updateStateWithContest("live");
            } 
        }
    }

    public static function inProgressMatches(){
        $holders = CodeHolder::liveOrUpcoming();
        $cc = 1;
        $length = count($holders);

        $matches_map = [];

        foreach ($holders as $holder){
            print_r($holder->yCode);
            $matches_map = self::refreshMatches($holder->ych_code,$matches_map);
            print_r(" loading: ".(int)($cc/$length*100)."%\n");
            $cc += 1;
        }
    }

    public static function statusLiveToCompleted(){
        $codeHolders = CodeHolder::live();
        foreach ($codeHolders as $codeHolder){
            if (self::isfinishedMatches($codeHolder->matches)){
                $codeHolder->updateStateWithContest("completed");
            }
        }
    }


    private static function isfinishedMatches($nbaContestMatches){
        $cc = count($nbaContestMatches);
        $tooluur = 0;
        foreach ($nbaContestMatches as $match){
            $tooluur += (strcasecmp($match->mtc_status_type,"FINAL") == 0 || strcasecmp($match->mtc_status_type,"POSTPONED") == 0) ? 1 : 0;
        }
        return $cc == $tooluur;
    }

    public static function refreshMatches($ych_code,$matches_map){
        $client = new Client();
        $url = 'https://{contest-stat-service-link}/v2/contest/'.$ych_code;
        echo "x: ".$ych_code."\n";
        $res = $client->request('GET', $url, []);
        $data = json_decode($res->getBody(), false);
        $matches = $data ->contests->result[0]->series->games;
        print_r(" ". count($matches));

        foreach ($matches as $key => $value) {
            $match = Match::match($ych_code,$value->code);
            if ($match){
                if ($value->statusType != 'PREGAME' && !array_key_exists($match->mtc_code, $matches_map)){
                    $matches_map[$match->mtc_code] = true;
                    self::getJsonBoxScore($match->mtc_code);
                }
                try{
                    $match->mtc_home_team_code = $value->homeTeamCode;
                    $match->mtc_away_team_code = $value->awayTeamCode;
                    $match->mtc_status_type = $value->statusType;
                    $match->mtc_status = $value->status;
                    $match->mtc_lineup_available = $value->lineupAvailable;
                    $match->mtc_start_time = date('Y-m-d H:i:s T',$value->startTime/1000);
                    $match->mtc_location = $value->location;

                    if (property_exists($value,"homeScore") && property_exists($value,"awayScore")){
                        $match->mtc_home_score = $value->homeScore;
                        $match->mtc_away_score = $value->awayScore;  
                    }
                        
                    $match->save();
                    echo "save ".$match->mtc_id."\n";

                }catch(Exception $e){
                    echo 'Message: ' .$e->errorInfo();
                }

            }
        }
        return $matches_map;
    }

    public static function getJsonBoxScore($match_code){
        $client = new Client();
        $url = 'https://{link-to-service-provider}/boxscore/'.$match_code;

        $res = $client->request('GET', $url, []);
        
        $boxscore = json_decode($res->getBody(), false)->service->boxscore;
        
        if (array_key_exists("player_stats", $boxscore)) {
            $player_stats = $boxscore->player_stats;
            foreach($player_stats as $key => $player_var_s){
                $player_stat = $player_var_s->{'nba.stat_variation.2'};

                $playerStats = new NbaPlayerStat;
                $playerStats->nps_game_code = $match_code;
                $playerStats->nps_player_code = $key;

                $tmp = NbaPlayerStat::getState($match_code,$key);
                if (isset($tmp->nps_id)){
                    $playerStats = $tmp;
                }
                self::loadPlayerState($playerStats,$key,$player_stat);
                $playerStats->save();
            }
        }
    }


    private static function loadPlayerState($playerStats,$key,$value){
        $playerStats->nps_min = $value->{"nba.stat_type.3"};
        $playerStats->nps_threept = explode("-",$value->{"nba.stat_type.30"})[0];
        $playerStats->nps_reb = $value->{"nba.stat_type.16"};
        $playerStats->nps_ast = $value->{"nba.stat_type.17"};
        $playerStats->nps_to = $value->{"nba.stat_type.20"};
        $playerStats->nps_st = $value->{"nba.stat_type.18"};
        $playerStats->nps_blk = $value->{"nba.stat_type.19"};
        $playerStats->nps_pt = $value->{"nba.stat_type.13"};

        $playerStats->nps_fpts = $playerStats->nps_pt 
                                                + 0.5*$playerStats->nps_threept
                                                + 1.2*$playerStats->nps_reb 
                                                + 1.5*$playerStats->nps_ast 
                                                + 2*$playerStats->nps_st 
                                                + 2*$playerStats->nps_blk
                                                - $playerStats->nps_to;
    }

}
