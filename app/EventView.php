<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventView extends Model
{
    protected $fillable = [
        'event_id', 'time', 'time_status', 'league_id', 'home', 'away', 'timer', 'stats', 'events', 'away_m', 'home_m', 'referee', 'round', 'stadium',
    ];

    protected $dates = [
        'time'
    ];

    public  function league()
    {
        return $this->belongsTo('App\League');
    }

    public  function event()
    {
         return $this->belongsTo('App\Event', 'event_id', 'event_id');
    }

}
