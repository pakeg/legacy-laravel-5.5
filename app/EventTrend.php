<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventTrend extends Model
{
    protected $fillable = [
        'event_id', 'goals', 'yellowcards', 'redcards', 'substitutions',
    ];

}
