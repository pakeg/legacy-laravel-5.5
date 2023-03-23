<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ToplistCard extends Model
{
    protected $fillable = [
        'player_id', 'red_cards', 'yellow_cards'
    ];
}
