<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title', 'description', 'image', 'viewed',
    ];

    protected $dates = [
        'published_at'
    ];
   
    public  function user()
    {
         return $this->belongsTo('App\User');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Tag')->withTimestamps();
    }

    public  function comments()
    {
         return $this->hasMany('App\Comment');
    }

    public  function video()
    {
         return $this->hasOne('App\Video');
    }

    public  function photo()
    {
         return $this->hasMany('App\Photo');
    }
}
