<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $guarded = [];

    // Function to get Post
    public function getPost(){

        return $this->belongsTo('App\Post');// Model Post
    }
}
