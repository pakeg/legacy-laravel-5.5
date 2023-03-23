<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'name', 'image',
    ];

    public  function homeE()
    {
        return $this->hasMany('App\Event', 'home');
    }

    public  function awayE()
    {
        return $this->hasMany('App\Event', 'away');
    }

    public  function country()
    {
        return $this->belongsTo('App\Country');
    }

    public  function players()
    {
        return $this->hasMany('App\Player');
    }

}
