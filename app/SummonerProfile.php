<?php

namespace App;

class SummonerProfile {

	public $summonerName;
	public $summonerLevel;
	public $profilePicture;
	public $tier;
	public $division;
	public $leaguePoints;

	public function _construct($summonerName, $summonerLevel, $profilePicture, $tier, $division, $leaguePoints)
	{
		$this->summonerName = $summonerName;
		$this->summonerLevel = $summonerLevel;
		$this->profilePicture = $profilePicture;
		$this->tier = $tier;
		$this->division = $division;
		$this->leaguePoints = $leaguePoints;
	}
}