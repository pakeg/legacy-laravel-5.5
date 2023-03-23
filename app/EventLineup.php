<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventLineup extends Model
{
    protected $fillable = [
        'event_id', 'home_start', 'away_start', 'home_subs', 'away_subs',
    ];

}
