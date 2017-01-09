<?php

namespace App;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements Authenticatable //Authenticatable Imports bundle of functions
{

	//Access database without specifying anything
	use \Illuminate\Auth\Authenticatable;
	
	
	public function posts()
	{
		return $this->hasMany('App\Post'); //Sets up a relation which tells that User model can "own" many Posts models
	}
	public function likes()
	{
		return $this->hasMany('App\Like');
	}
	public function comments()
	{
		return $this->hasMany('App\Comment');
	}
}
