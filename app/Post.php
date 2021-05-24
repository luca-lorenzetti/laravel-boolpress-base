<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = [];

    
    // Function to get comments
    public function getComments(){

        return $this->hasMany('App\Comment');// Model Comment
    }

}
