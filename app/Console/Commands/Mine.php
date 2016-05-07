<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\SummonerProfile;
use App\gamestat;

use LeagueWrap\Api;

use SplQueue;

class Mine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mine';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mines the League of Legends Api';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
    * Converts errors into exceptions so that they can be caught
    */
    function exception_error_handler($errno, $errstr, $errfile, $errline)
    {
	throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
	set_error_handler(array($this, "exception_error_handler"));
	
	//Holds maximum allowed attempts for a valid API response before quitting
	$MAX_ATTEMPTS = 5;
	$attempts = 0;
	
	$api = new Api(env('API_KEY'));
		
	echo "Creating League API Object\n";
	//TODO: make seperate commands to mine other servers
	$api->setRegion('na');
	
	$matchlistApi = $api->matchlist();
	$summonerQueue = new SplQueue();
	$summonerQueue->enqueue(29802427);	
	echo "Iterating Through Summoner Queue\n";
	
	while(!$summonerQueue->isEmpty()) {
		echo "Current Queue Size: " . $summonerQueue->count() . "\n";
		
		$currentSummoner = $summonerQueue->dequeue();
		echo "Geting MatchList Data For Summoner: " . $currentSummoner . "\n";
		
		$matchList = null;
		$staticData = null;

		do {
			try {
				$matchList = $matchlistApi->matchlist($currentSummoner, "TEAM_BUILDER_DRAFT_RANKED_5x5", "SEASON2016");
				$staticData = $api->staticData();
			} catch (\Exception $e) {
				//Wait for API to get back up or rate limit to reset
				echo "Error Encountered Getting Summoners Match History, Trying Again In 10 sec\n";
				sleep(10);
				$attempts++;
				continue;
			}
			
			$attempts = 0;
			break;

		} while($attempts < $MAX_ATTEMPTS);
		
		echo "Iterating Through Matches (" . count($matchList) .  ") For Summoner: " . $currentSummoner . "\n";
		//Iterating through each match found for the dequeued user
		for($i = 0; $i < count($matchList); $i++) {
			
			//Saving matchId for current match to later retrive stats
			$matchId = $matchList->match($i)->matchId;
			
			//Used to limit number of api requests we send	
			usleep(2000000);
			
			echo "Retrieving Match: " . $matchId . "\n";

			//Geting match stats 
			$matchApi = $api->match();
			$match = null;

			do {
				try {
					$match = $matchApi->match($matchId);
				} catch (\Exception $e) {
					echo "Error Encountered Retriving Match Data, Trying Again In 10 sec\n";
					sleep(10);
					$attempts++;
					continue;
				}
			
				$attempts = 0;
				break;
			
			} while($attempts < $MAX_ATTEMPTS);
			
			$region = $match->region;
			$matchDuration = $match->matchDuration;
			$season = $match->season;

			$summonersInMatch = array();

			for($j = 1; $j <= count($match->participants); $j++) {
				echo "Retrieving Stats For Participant " . $j . " In Match: " . $matchId . "\n";
					
				$participantStats = $match->participants[$j]->stats;
				$participantTimeline = $match->participants[$j]->timeline;
				$participantIdentity = $match->participantIdentities[$j];
				$championId = $match->participants[$j]->championId;
				$championName = $staticData->getChampions()->data[$championId]->key;
				
				$summonerId = $participantIdentity->player['summonerId'];
				array_push($summonersInMatch, $summonerId);

				
				if(!in_array($summonerId, iterator_to_array($summonerQueue)))
					$summonerQueue->enqueue($summonerId);			
				
				//Getting Mastery Information From Summoner
								

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
	
				echo "Attempting To Save New Record\n";				

				//Try to add row into database, if already in db then dont add
				try {
					$gamestat = gamestat::create(['summonerId' => $summonerId,
								      'matchId' => $matchId,
								      'region' => $region,
								      'matchDuration' => $matchDuration,
								      'season' => $season,
								      'lane' => $lane,
								      'role' => $role,
								      'championId' => $championId,
								      'champion' => $championName,
								      'doubles' => $doubleKills,
								      'triples' => $tripleKills,
								      'quadras' => $quadraKills,
								      'pentas' => $pentaKills, 
								      'wardsBought' => $visionWardsBoughtInGame,
								      'wardsPlaced' => $wardsPlaced,
								      'wardsKilled' => $wardsKilled,
								      'damageDealt' => $totalDamageDealtToChampions,
								      'damageTaken' => $totalDamageTaken,
								      'crowdControlTimeDealt' => $totalTimeCrowdControlDealt]);
				
				echo "Entry Saved For Summoner: " . $summonerId . " In Match: " . $matchId . "\n";
				} catch (\Illuminate\Database\QueryException $e) {
					echo "Duplicate Entry Not Added \n";
				}	
			}
			echo "Retrieving User Rank Information\n";
			
			$leagueApi = $api->league();
			$userLeagueData = null;

			do {
				try {
					$userLeagueData = (array) $leagueApi->league($summonersInMatch, true);
				} catch(\Exception $e) {
					echo "Error Encountered Getting Ranked Info, Trying Again In 10 sec\n";
					sleep(10);
					$attempts++;
					continue;
				}

				$attempts = 0;
				break;

			} while($attempts < $MAX_ATTEMPTS);

			for($k = 0; $k < count($summonersInMatch); $k++) {
				//Check to see if user has a rank else delete row
				if(isset($userLeagueData[$summonersInMatch[$k]])) {
					$division = $userLeagueData[$summonersInMatch[$k]][0]->entries[0]->division;
					$tier = $userLeagueData[$summonersInMatch[$k]][0]->tier;

					gamestat::where('matchId', $matchId)
						->where('summonerId', $summonersInMatch[$k])
						->update(['tier' => $tier, 'division' => $division]);
					echo "Successfully Added Rank Info For: " . $summonersInMatch[$k] . "\n";
				}
				else {
					echo "Rank Info For: " . $summonersInMatch[$k] . " Was Not Found, Deleting Record\n";					

					gamestat::where('matchId', $matchId)
						->where('summonerId', $summonersInMatch[$k])
						->delete();
				}
			}
		}
	}	
    }
}
