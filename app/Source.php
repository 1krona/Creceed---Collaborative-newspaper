<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    public function post()
    {
        return $this->belongsTo('App\Post');
    }
    public function likes()
    {
        return $this->hasMany('App\Like');
    }
}