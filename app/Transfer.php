<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $fillable = [
        'player_id', 'start', 'end', 'type_id', 'active', 'to', 'from', 'cost',
    ];

    protected $dates = [
        'start', 'end'
    ];

    public  function player()
    {
        return $this->belongsTo('App\Player');
    }

    public  function teamTo()
    {
        return $this->belongsTo('App\Team', 'to');
    }

    public  function teamFrom()
    {
        return $this->belongsTo('App\Team', 'from');
    }
}
