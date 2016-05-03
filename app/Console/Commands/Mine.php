<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\SummonerProfile;
use App\gamestat;

use LeagueWrap\Api;

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
	$api = new Api(env('LEAGUE_API_KEY'));
	
	//TODO: make seperate commands to mine other servers
	$api->setRegion('na');
	
	$matchlistApi = $api->matchlist();
	$summonerQueue = new SplQueue();
	
	while(!$summonerQueue->isEmpty()) {
		//TODO: check season name and queue type
		$matchlist = $matchlistApi->matchlist($summonerQueue->dequeue(), "RANKED_SOLO_5x5", "SEASON2016");
		$staticData = $api->staticData();
		
		//Iterating through each match found for the dequeued user
		for($i = 0; $i < count($matchlist); $i++) {
			
			//Saving matchId for current match to later retrive stats
			$matchId = $matchList->($i)->matchId;
			
			//Used to limit number of api requests we send	
			usleep(2000000);

			//Geting match stats 
			$matchApi = $api->match();
			$match = $matchApi->match($matchId);
			
			$region = $match->region;
			$matchDuration = $match->matchDuration;
			$season = $match->season;

			$summonersInMatch = array();
			for($j = 1; $j <= count($match->participants); $j++) {
				$participantStats = $match->participants[$j->stats;
				$participantTimeline = $match->participants[$j]->timline;
				$participantIdentity = $match->participantIdentities[$j];
				$champId = $match->participants[$j]->championId;
				$championName = $staticData->getChampions()->data[$champId]->key;
				
				$summonerId = $participantIdentity->player['summonerId'];
				array_push($summonersInMatch, $summonerId);
		
				$lane = $participantTimeline->lane;
				$role = $participantTimeline->role;
				$kills = $participantStats->kills;
				$deaths = $participantStats->deaths;
				$assists = $participants->assists;
				$minionsKilled = $participantStats->minionsKilled;
				$neutralMinionsKilled - $participantStats->neutralMinionsKilled;
				$creepScore = $minionsKilled + $neutralMinionsKilled;
				$goldEarned = $participantStats->goldEarned;
				$doubleKills = $participantStats->doubleKills;
				$tripleKills = $paticipantStats->tripleKills;
				$quadraKills = $participantStats->quadraKills;
				$pentaKills = $participantStats->pentaKills;
				$visionsWardsBoughtInGame = $participantStats->visionWardsBoughtInGame;
				$wardsPlaced = $participantStats->wardsPlaced;
				$wardsKilled = $participantStats->wardsKilled;
				$totalDamageDealtToChampions = $participantStats->totalDamageDealtToChampions;
				$totalDamageTaken = $participantStats->totalDamageTaken;
				$totalTimeCrowdControlDealt = $participantStats->totalTimeCrowdControlDealt;
	
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
				} catch (\Illuminate\Database\QueryException $e) {
					echo "Duplicate Entry Not Added";
				}	
			}





		}
	}	
    }
}
