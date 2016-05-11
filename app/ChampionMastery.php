<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChampionMastery extends Model
{
    protected $table = 'championMastery';
    public $timestamps = false;
    protected $fillable = ['championId', 'championName', 'totalMastery', 'totalLevel', 'totalChests', 'count'];
}
