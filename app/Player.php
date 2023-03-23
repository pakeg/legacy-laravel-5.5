<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'name', 'country_id', 'team_id', 'position', 'height', 'weight', 'foot', 'shirtnumber',
    ];

    protected $dates = [
        'birthdate'
    ];

    public  function toplist()
    {
        return $this->hasOne('App\ToplistGoal');
    }

    public  function team()
    {
        return $this->belongsTo('App\Team');
    }

    public  function country()
    {
        return $this->belongsTo('App\Country');
    }

    public  function transfers()
    {
        return $this->hasMany('App\Transfer');
    }
}
