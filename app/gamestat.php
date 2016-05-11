<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class gamestat extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gamestats';


    protected $fillable = ['summonerId', 'matchId', 'region', 'matchDuration', 'season', 'tier', 'division', 'lane', 'role', 'championId', 'champion', 
    						'kills', 'deaths', 'assists', 'creepScore', 'goldEarned', 'doubles', 'triples', 'quadras', 'pentas',
    						'wardsBought', 'wardsPlaced', 'wardsKilled', 'damageDealt', 'damageTaken', 'crowdControlTimeDealt'];

}
