<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    protected $fillable = [
        'name', 'country_id', 'sort_order', 
    ];

    public  function country()
    {
        return $this->belongsTo('App\Country');
    }

    public function event ()
    {
           return $this->hasMany('App\Event');
    }

    public function eventView ()
    {
           return $this->hasMany('App\EventView');
    }

    public function leagueTable ()
    {
           return $this->hasOne('App\LeagueTable');
    }

    public function toplist ()
    {
           return $this->hasOne('App\LeagueToplist');
    }

}
