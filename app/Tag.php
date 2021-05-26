<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $guarded = [];

    // Function to get Post
    public function posts()
    {
        return $this->belongsToMany('App\Post');
    }
}
