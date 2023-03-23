<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ToplistGoal extends Model
{
    protected $fillable = [
        'league_id', 'goal_points', 'goals', 'penalties', 'matches', 'minutes_played', 'substituted_in', 'player_id', 'team_id'
    ];

    public  function league()
    {
      return $this->belongsTo('App\League');
    }

    public  function player()
    {
      return $this->belongsTo('App\Player');
    }

    public  function team()
    {
      return $this->belongsTo('App\Team');
    }

}
