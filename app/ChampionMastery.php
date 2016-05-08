<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChampionMastery extends Model
{
    protected $table = 'championMastery';

    protected $fillable = ['championId', 'championName', 'totalMastery', 'totalLevel', 'totalChests', 'count'];
}
