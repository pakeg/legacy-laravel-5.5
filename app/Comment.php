<?php

namespace App;

use Cog\Likeable\Contracts\Likeable as LikeableContract;
use Cog\Likeable\Traits\Likeable;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model implements LikeableContract
{
    use Likeable;
    
    protected $fillable = [
        'text',  
    ];

   
    public  function user()
    {
        return $this->belongsTo('App\User');
    }

    public function post()
    {
        return $this->belongsTo('App\Post');
    }
}
