<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeagueToplist extends Model
{
    protected $fillable = [
        'league_id', 'goal_points', 'goals', 'penalties', 'matches', 'minutes_played', 'substituted_in', 'player', 'team_id', 'assists', 'red_cards', 'yellow_cards'
    ];

    public  function league()
    {
        return $this->belongsTo('App\League');
    }

}
