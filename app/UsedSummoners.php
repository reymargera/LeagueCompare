<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsedSummoners extends Model
{
    protected $table = 'usedSummoners';

    public $timestamps = false;

    protected $fillable = ['summonerId'];
}
