<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'event_id', 'time', 'time_status', 'league_id', 'home', 'away', 'timer', 'scores_home', 'scores_away',
    ];

    protected $dates = [
        'time'
    ];

    public  function league()
    {
        return $this->belongsTo('App\League');
    }

    public  function comments()
    {
         return $this->hasMany('App\CommentEvent');
    }

    public  function homeT()
    {
         return $this->belongsTo('App\Team', 'home');
    }

    public  function awayT()
    {
         return $this->belongsTo('App\Team', 'away');
    }

    public  function eventView()
    {
         return $this->hasOne('App\EventView', 'event_id', 'event_id');
    }

}
