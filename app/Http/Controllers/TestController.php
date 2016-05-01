<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use LeagueWrap\Api;
use App\SummonerProfile;

use App\gamestat;

class TestController extends Controller
{

	/**
	 * @return string
     */
	public function get() {

    	$api = new Api('b38ffe8a-b2c7-4272-a3d2-fb0c798823c0');
    	$api->setRegion('na');
    	//return View:make('index');
    	$matchlistApi = $api->matchlist();
    	$summonerArray = new SplQueue();
    	$summonerArray->enqueue(21174748);
    	

    	foreach($summonerArray as $summoner) {

			$matchlist = $matchlistApi->matchlist($summoner, "RANKED_SOLO_5x5", "PRESEASON2016");
			$staticData = $api->staticData();


			//Iterate through each match in list
			for ($i = 0; $i < count($matchlist); $i++) {


				//get matchId for each match to look up stats 
				$matchId = $matchlist->match($i)->matchId;
				
				usleep(2000000);

				//get specific match to extract stats
				$matchApi = $api->match();
				$match = $matchApi->match($matchId);

				//stats extraction
				$region = $match->region;
				$matchDuration = $match->matchDuration;
				$season = $match->season;

				$summonerIdList = array();
				$currentSummoner = $summonerArray->dequeue();

				for ($j = 1; $j <= count($match->participants); $j++) {
					$participantStats =  $match->participants[$j]->stats;
					$participantTimeline = $match->participants[$j]->timeline;
					$participantIdentity = $match->participantIdentities[$j];
					$champId = $match->participants[$j]->championId;
					$championName = $staticData->getChampions()->data[$champId]->key;

					$summonerId = $participantIdentity->player['summonerId'];
					array_push($summonerIdList, $summonerId);
					if($summonerId != $currentSummoner)
						$summonerArray->enqueue($summonerId);

					$lane = $participantTimeline->lane;
					$role = $participantTimeline->role;
					$kills = $participantStats->kills;
					$deaths = $participantStats->deaths;
					$assists = $participantStats->assists;
					$minionsKilled = $participantStats->minionsKilled;
					$neutralMinionsKilled = $participantStats->neutralMinionsKilled;
					$creepScore = $minionsKilled + $neutralMinionsKilled;
					$goldEarned = $participantStats->goldEarned;
					$doubleKills = $participantStats->doubleKills;
					$tripleKills = $participantStats->tripleKills;
					$quadraKills = $participantStats->quadraKills;
					$pentaKills = $participantStats->pentaKills;
					$visionWardsBoughtInGame = $participantStats->visionWardsBoughtInGame;
					$wardsPlaced = $participantStats->wardsPlaced;
					$wardsKilled = $participantStats->wardsKilled;
					$totalDamageDealtToChampions = $participantStats->totalDamageDealtToChampions;
					$totalDamageTaken = $participantStats->totalDamageTaken;
					$totalTimeCrowdControlDealt = $participantStats->totalTimeCrowdControlDealt;

					try {

						$gamestat = gamestat::create(['summonerId' => $summonerId, 'matchId' => $matchId, 'region' => $region, 'matchDuration' => $matchDuration,
							'season' => $season, 'lane' => $lane, 'role' => $role, 'championId' => $champId, 'champion' => $championName,
							'kills' => $kills, 'deaths' => $deaths, 'assists' => $assists, 'creepScore' => $creepScore, 'goldEarned' => $goldEarned,
							'doubles' => $doubleKills, 'triples' => $tripleKills, 'quadras' => $quadraKills, 'pentas' => $pentaKills,
							'wardsBought' => $visionWardsBoughtInGame, 'wardsPlaced' => $wardsPlaced, 'wardsKilled' => $wardsKilled, 'damageDealt' => $totalDamageDealtToChampions,
							'damageTaken' => $totalDamageTaken, 'crowdControlTimeDealt' => $totalTimeCrowdControlDealt]);
					} catch (\Illuminate\Database\QueryException $e) {
						echo "Duplicate Entry Avoided";
					}
				}

				$leagueApi = $api->league();
	    		$userLeagueData = (array) $leagueApi->league($summonerIdList, true);

	    		for($k = 0; $k < count($summonerIdList); $k++) {
	    			//Check to see if they have a rank if not delete record
	    			if(isset($userLeagueData[$summonerIdList[$k]])) {

	    				$division = $userLeagueData[$summonerIdList[$k]][0]->entries[0]->division;
	    				$tier = $userLeagueData[$summonerIdList[$k]][0]->tier;

		    			gamestat::where('matchId', $matchId)
		          				   ->where('summonerId', $summonerIdList[$k])
		          				   ->update(['tier' => $tier, 'division' => $division]);
	    				
	    			} else {
	    				gamestat::where('matchId', $matchId)
		          				   ->where('summonerId', $summonerIdList[$k])
		          				   ->delete();
	    			}	
	    		}    		
			}
		}
		return 'done';
    }

    public function post() {
    	$username = Input::get('username');
    	$region = Input::get('region');

    	//call summoner endpoint to get basic user info
    	$api->setRegion($region);
    	$summonerApi = $api->summoner();
    	$userData = $summonerApi->info($username);

    	//call league endpoint to get users current div and tier
    	$leagueApi = $api->league();
    	$userLeagueData = $leagueApi->league($userData->id, true);

    	//$summoner = new SummonerProfile($userData->name, $userData->summonerLevel, $userData->profileIconId)

    }
}
