<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function user()
	{
		return $this->belongsTo('App\User'); // Tells that this model Posts belongs to something, in this case a User
	}
	
	public function likes() // Creates relationship on column "post_id" which get accessed through $post->like
	{
		return $this->hasMany('App\Like');
	}

	public function comments()
	{
		return $this->hasMany('App\Comment');
	}
	public function sources()
	{
		return $this->hasMany('App\Source');
	}
}
