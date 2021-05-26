<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = ['tags'];

    
    // Function to get comments
    public function comments(){

        return $this->hasMany('App\Comment');// Model Comment
    }

    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }
}
