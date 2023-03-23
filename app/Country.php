<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'name', 'cc', 'image', 
    ];

    public  function league()
    {
        return $this->hasMany('App\League');
    }

    public  function teams()
    {
         return $this->hasMany('App\Team');
    }

    public  function players()
    {
         return $this->hasMany('App\Player');
    }

}
