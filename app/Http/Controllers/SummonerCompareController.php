<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use DB;
use View;

use LeagueWrap\Api;

class SummonerCompareController extends Controller
{
   
   public function index($summonerId)
   {
	//Variables to hold summoners stat info
	$totalKills = $totalDeaths = $totalAssists = $totalCreepScore = $totalGold = 0;
	$totalDoubles = $totalTriples = $totalQuadras = $totalPentas = 0;
	$totalWardsBought = $totalWardsPlaced = $totalWardsKilled = 0;
	$totalDamageDealt = $totalDamageTaken = $totalCCDealt = 0;

	$api = new Api(env('API_KEY'));
	$api->setRegion('na');	

	$matchListApi = $api->matchlist();
	$matchList = $matchListApi->matchlist($summonerId, 'TEAM_BUILDER_DRAFT_RANKED_5x5', 'SEASON2016');
	
	//Holds how many games we will iterate through
	$limit = 0;

	if(count($matchList) > 10)
		$limit = 10;
	else
		$limit = count($matchList);
	
	for($i = 0; $i < $limit; $i++) {
		$matchId = $matchList->match($i)->matchId;
		
		$matchApi = $api->match();
		$match = $matchApi->match($matchId);
		usleep(1000000);	
		$participantId = 0;
		for($j = 1; $j <= count($match->participantIdentities); $j++) {
			$participant = $match->participantIdentities[$j];
			
			if($participant->player['summonerId'] == $summonerId) {
				$participantId = $j;
				break;
			}
		}
		
		$participantStats = $match->participants[$participantId]->stats;
		
		$totalKills += $participantStats->kills;
		$totalDeaths += $participantStats->deaths;
		$totalAssists += $participantStats->assists;
		
		$totalCreepScore += $participantStats->minionsKilled;
		$totalCreepScore += $participantStats->neutralMinionsKilled;
		
		$totalGold += $participantStats->goldEarned;
		$totalDoubles += $participantStats->doubleKills;
		$totalTriples += $participantStats->tripleKills;
		$totalQuadras += $participantStats->quadraKills;
		$totalPentas += $participantStats->pentaKills;
		
		$totalWardsBought += $participantStats->visionWardsBoughtInGame;
		$totalWardsPlaced += $participantStats->wardsPlaced;
		$totalWardsKilled += $participantStats->wardsKilled;
		
		$totalDamageDealt += $participantStats->totalDamageDealtToChampions;
		$totalDamageTaken += $participantStats->totalDamageTaken;
		$totalCCDealt += $participantStats->totalTimeCrowdControlDealt;
	}
	
	$summonerAverageStats = [
		'kills' => $totalKills/$limit,
		'deaths' => $totalDeaths/$limit,
		'assists' => $totalAssists/$limit,
		'creepScore' => $totalCreepScore/$limit,
		'gold' => $totalGold/$limit,
		'doubles' => $totalDoubles/$limit,
		'triples' => $totalTriples/$limit,
		'quadras' => $totalQuadras/$limit,
		'pentas' => $totalPentas/$limit,
		'wardsBought' => $totalWardsBought/$limit,
		'wardsPlaced' => $totalWardsPlaced/$limit,
		'wardsKilled' => $totalWardsKilled/$limit,
		'damageDealt' => $totalDamageDealt/$limit,
		'damageTaken' => $totalDamageTaken/$limit,
		'ccDealt' => $totalCCDealt/$limit,
	];
	
	$globalAverageStats = $this->getGlobalAverages();
	$categories = ['kills', 'deaths', 'assists', 'creepScore', 'gold', 'doubles', 'triples', 'quadras', 'pentas', 'wardsBought', 'wardsPlaced', 'wardsKilled', 'damageDealt', 'damageTaken', 'ccDealt'];	
	return View::make('compare', array('summonerAvg' => $summonerAverageStats, 'globalAvg' => $globalAverageStats, 'categories' => $categories));
   }
	
   public function getGlobalAverages()
   {
	$bronzeAverage = [
		'kills' => DB::table('gamestats')->where('tier', 'BRONZE')->avg('kills'),
		'deaths' => DB::table('gamestats')->where('tier', 'BRONZE')->avg('deaths'),
		'assists' => DB::table('gamestats')->where('tier', 'BRONZE')->avg('assists'),
		'creepScore' => DB::table('gamestats')->where('tier', 'BRONZE')->avg('creepScore'),
		'gold' => DB::table('gamestats')->where('tier', 'BRONZE')->avg('goldEarned'),
		'doubles' => DB::table('gamestats')->where('tier', 'BRONZE')->avg('doubles'),
		'triples' => DB::table('gamestats')->where('tier', 'BRONZE')->avg('triples'),
		'quadras' => DB::table('gamestats')->where('tier', 'BRONZE')->avg('quadras'),
		'pentas' => DB::table('gamestats')->where('tier', 'BRONZE')->avg('pentas'),
		'wardsBought' => DB::table('gamestats')->where('tier', 'BRONZE')->avg('wardsBought'),
		'wardsPlaced' => DB::table('gamestats')->where('tier', 'BRONZE')->avg('wardsPlaced'),
		'wardsKilled' => DB::table('gamestats')->where('tier', 'BRONZE')->avg('wardsKilled'),
		'damageDealt' => DB::table('gamestats')->where('tier', 'BRONZE')->avg('damageDealt'),
		'damageTaken' => DB::table('gamestats')->where('tier', 'BRONZE')->avg('damageTaken'),		
		'ccDealt' => DB::table('gamestats')->where('tier', 'BRONZE')->avg('crowdControlTimeDealt'),
	];

	$silverAverage = [
		'kills' => DB::table('gamestats')->where('tier', 'SILVER')->avg('kills'),
		'deaths' => DB::table('gamestats')->where('tier', 'SILVER')->avg('deaths'),
		'assists' => DB::table('gamestats')->where('tier', 'SILVER')->avg('assists'),
		'creepScore' => DB::table('gamestats')->where('tier', 'SILVER')->avg('creepScore'),
		'gold' => DB::table('gamestats')->where('tier', 'SILVER')->avg('goldEarned'),
		'doubles' => DB::table('gamestats')->where('tier', 'SILVER')->avg('doubles'),
		'triples' => DB::table('gamestats')->where('tier', 'SILVER')->avg('triples'),
		'quadras' => DB::table('gamestats')->where('tier', 'SILVER')->avg('quadras'),
		'pentas' => DB::table('gamestats')->where('tier', 'SILVER')->avg('pentas'),
		'wardsBought' => DB::table('gamestats')->where('tier', 'SILVER')->avg('wardsBought'),
		'wardsPlaced' => DB::table('gamestats')->where('tier', 'SILVER')->avg('wardsPlaced'),
		'wardsKilled' => DB::table('gamestats')->where('tier', 'SILVER')->avg('wardsKilled'),
		'damageDealt' => DB::table('gamestats')->where('tier', 'SILVER')->avg('damageDealt'),
		'damageTaken' => DB::table('gamestats')->where('tier', 'SILVER')->avg('damageTaken'),		
		'ccDealt' => DB::table('gamestats')->where('tier', 'SILVER')->avg('crowdControlTimeDealt'),
	];

	$goldAverage = [
		'kills' => DB::table('gamestats')->where('tier', 'GOLD')->avg('kills'),
		'deaths' => DB::table('gamestats')->where('tier', 'GOLD')->avg('deaths'),
		'assists' => DB::table('gamestats')->where('tier', 'GOLD')->avg('assists'),
		'creepScore' => DB::table('gamestats')->where('tier', 'GOLD')->avg('creepScore'),
		'gold' => DB::table('gamestats')->where('tier', 'GOLD')->avg('goldEarned'),
		'doubles' => DB::table('gamestats')->where('tier', 'GOLD')->avg('doubles'),
		'triples' => DB::table('gamestats')->where('tier', 'GOLD')->avg('triples'),
		'quadras' => DB::table('gamestats')->where('tier', 'GOLD')->avg('quadras'),
		'pentas' => DB::table('gamestats')->where('tier', 'GOLD')->avg('pentas'),
		'wardsBought' => DB::table('gamestats')->where('tier', 'GOLD')->avg('wardsBought'),
		'wardsPlaced' => DB::table('gamestats')->where('tier', 'GOLD')->avg('wardsPlaced'),
		'wardsKilled' => DB::table('gamestats')->where('tier', 'GOLD')->avg('wardsKilled'),
		'damageDealt' => DB::table('gamestats')->where('tier', 'GOLD')->avg('damageDealt'),
		'damageTaken' => DB::table('gamestats')->where('tier', 'GOLD')->avg('damageTaken'),		
		'ccDealt' => DB::table('gamestats')->where('tier', 'GOLD')->avg('crowdControlTimeDealt'),
	];

	$platAverage = [
		'kills' => DB::table('gamestats')->where('tier', 'PLATINUM')->avg('kills'),
		'deaths' => DB::table('gamestats')->where('tier', 'PLATINUM')->avg('deaths'),
		'assists' => DB::table('gamestats')->where('tier', 'PLATINUM')->avg('assists'),
		'creepScore' => DB::table('gamestats')->where('tier', 'PLATINUM')->avg('creepScore'),
		'gold' => DB::table('gamestats')->where('tier', 'PLATINUM')->avg('goldEarned'),
		'doubles' => DB::table('gamestats')->where('tier', 'PLATINUM')->avg('doubles'),
		'triples' => DB::table('gamestats')->where('tier', 'PLATINUM')->avg('triples'),
		'quadras' => DB::table('gamestats')->where('tier', 'PLATINUM')->avg('quadras'),
		'pentas' => DB::table('gamestats')->where('tier', 'PLATINUM')->avg('pentas'),
		'wardsBought' => DB::table('gamestats')->where('tier', 'PLATINUM')->avg('wardsBought'),
		'wardsPlaced' => DB::table('gamestats')->where('tier', 'PLATINUM')->avg('wardsPlaced'),
		'wardsKilled' => DB::table('gamestats')->where('tier', 'PLATINUM')->avg('wardsKilled'),
		'damageDealt' => DB::table('gamestats')->where('tier', 'PLATINUM')->avg('damageDealt'),
		'damageTaken' => DB::table('gamestats')->where('tier', 'PLATINUM')->avg('damageTaken'),		
		'ccDealt' => DB::table('gamestats')->where('tier', 'PLATINUM')->avg('crowdControlTimeDealt'),
	];

	$diamondAverage = [
		'kills' => DB::table('gamestats')->where('tier', 'DIAMOND')->avg('kills'),
		'deaths' => DB::table('gamestats')->where('tier', 'DIAMOND')->avg('deaths'),
		'assists' => DB::table('gamestats')->where('tier', 'DIAMOND')->avg('assists'),
		'creepScore' => DB::table('gamestats')->where('tier', 'DIAMOND')->avg('creepScore'),
		'gold' => DB::table('gamestats')->where('tier', 'DIAMOND')->avg('goldEarned'),
		'doubles' => DB::table('gamestats')->where('tier', 'DIAMOND')->avg('doubles'),
		'triples' => DB::table('gamestats')->where('tier', 'DIAMOND')->avg('triples'),
		'quadras' => DB::table('gamestats')->where('tier', 'DIAMOND')->avg('quadras'),
		'pentas' => DB::table('gamestats')->where('tier', 'DIAMOND')->avg('pentas'),
		'wardsBought' => DB::table('gamestats')->where('tier', 'DIAMOND')->avg('wardsBought'),
		'wardsPlaced' => DB::table('gamestats')->where('tier', 'DIAMOND')->avg('wardsPlaced'),
		'wardsKilled' => DB::table('gamestats')->where('tier', 'DIAMOND')->avg('wardsKilled'),
		'damageDealt' => DB::table('gamestats')->where('tier', 'DIAMOND')->avg('damageDealt'),
		'damageTaken' => DB::table('gamestats')->where('tier', 'DIAMOND')->avg('damageTaken'),		
		'ccDealt' => DB::table('gamestats')->where('tier', 'DIAMOND')->avg('crowdControlTimeDealt'),
	];

	$masterAverage = [
		'kills' => DB::table('gamestats')->where('tier', 'MASTER')->avg('kills'),
		'deaths' => DB::table('gamestats')->where('tier', 'MASTER')->avg('deaths'),
		'assists' => DB::table('gamestats')->where('tier', 'MASTER')->avg('assists'),
		'creepScore' => DB::table('gamestats')->where('tier', 'MASTER')->avg('creepScore'),
		'gold' => DB::table('gamestats')->where('tier', 'MASTER')->avg('goldEarned'),
		'doubles' => DB::table('gamestats')->where('tier', 'MASTER')->avg('doubles'),
		'triples' => DB::table('gamestats')->where('tier', 'MASTER')->avg('triples'),
		'quadras' => DB::table('gamestats')->where('tier', 'MASTER')->avg('quadras'),
		'pentas' => DB::table('gamestats')->where('tier', 'MASTER')->avg('pentas'),
		'wardsBought' => DB::table('gamestats')->where('tier', 'MASTER')->avg('wardsBought'),
		'wardsPlaced' => DB::table('gamestats')->where('tier', 'MASTER')->avg('wardsPlaced'),
		'wardsKilled' => DB::table('gamestats')->where('tier', 'MASTER')->avg('wardsKilled'),
		'damageDealt' => DB::table('gamestats')->where('tier', 'MASTER')->avg('damageDealt'),
		'damageTaken' => DB::table('gamestats')->where('tier', 'MASTER')->avg('damageTaken'),		
		'ccDealt' => DB::table('gamestats')->where('tier', 'MASTER')->avg('crowdControlTimeDealt'),
	];

	$challengerAverage = [
		'kills' => DB::table('gamestats')->where('tier', 'CHALLENGER')->avg('kills'),
		'deaths' => DB::table('gamestats')->where('tier', 'CHALLENGER')->avg('deaths'),
		'assists' => DB::table('gamestats')->where('tier', 'CHALLENGER')->avg('assists'),
		'creepScore' => DB::table('gamestats')->where('tier', 'CHALLENGER')->avg('creepScore'),
		'gold' => DB::table('gamestats')->where('tier', 'CHALLENGER')->avg('goldEarned'),
		'doubles' => DB::table('gamestats')->where('tier', 'CHALLENGER')->avg('doubles'),
		'triples' => DB::table('gamestats')->where('tier', 'CHALLENGER')->avg('triples'),
		'quadras' => DB::table('gamestats')->where('tier', 'CHALLENGER')->avg('quadras'),
		'pentas' => DB::table('gamestats')->where('tier', 'CHALLENGER')->avg('pentas'),
		'wardsBought' => DB::table('gamestats')->where('tier', 'CHALLENGER')->avg('wardsBought'),
		'wardsPlaced' => DB::table('gamestats')->where('tier', 'CHALLENGER')->avg('wardsPlaced'),
		'wardsKilled' => DB::table('gamestats')->where('tier', 'CHALLENGER')->avg('wardsKilled'),
		'damageDealt' => DB::table('gamestats')->where('tier', 'CHALLENGER')->avg('damageDealt'),
		'damageTaken' => DB::table('gamestats')->where('tier', 'CHALLENGER')->avg('damageTaken'),		
		'ccDealt' => DB::table('gamestats')->where('tier', 'CHALLENGER')->avg('crowdControlTimeDealt'),
	];
	
	$globalAverage = [
		'bronze' => $bronzeAverage,
		'silver' => $silverAverage,
		'gold' => $goldAverage,
		'plat' => $platAverage,
		'diamond' => $diamondAverage,
		'master' => $masterAverage,
		'challenger' => $challengerAverage,	
	];

	return $globalAverage;
   } 
}
