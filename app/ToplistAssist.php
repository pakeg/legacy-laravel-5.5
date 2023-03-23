<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ToplistAssist extends Model
{
    protected $fillable = [
        'player_id', 'assists'
    ];
}
