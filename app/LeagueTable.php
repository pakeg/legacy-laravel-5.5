<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeagueTable extends Model
{
    protected $fillable = [
        'league_id', 'start_time', 'end_time', 'overall', 'home', 'away', 'season',
    ];

    protected $dates = [
        'start_time', 'end_time',
    ];

    public  function league()
    {
        return $this->belongsTo('App\League');
    }

}
