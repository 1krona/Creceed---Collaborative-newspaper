<?php
namespace App\Http\Controllers;

use App\Comment;
use App\Category;
use App\Post;
use App\Like;
use App\Source;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{

	public function getDashboard()
	{

		$posts = Post::get();

		$posts = $posts->sortByDesc(function ($post) {
			return $post->likes->sum('like'); //Order by like summation score
		});

		// Adds 'draft_id' => NULL to avoid "Undefined variable" when displaying dashboard.
		return view('dashboard', ['posts' => $posts, 'draft_id' => NULL]);
	}

	public function getDashboardWithDraft($draft_id)
	{

		$posts = Post::get();

		$posts = $posts->sortByDesc(function ($post) {
			return $post->likes->sum('like'); //Order by like summation score
		});

		return view('dashboard', ['posts' => $posts, 'draft_id' => $draft_id]);
	}

	public function getHome()
	{

		$posts = Post::get()->where('published', 1);

		$posts = $posts->sortByDesc(function ($post) {
			return $post->likes->sum('like');
		});

		return view('welcome', ['posts' => $posts]);
	}

	public function getHomeByType($type)
	{

		$posts = Post::get();

		$posts = $posts->where('type', $type)->sortByDesc(function ($post) {
			return $post->likes->sum('like');
		});

		return view('welcome', ['posts' => $posts]);
	}

	public function postCreatePost(Request $request)
	{
		$this->validate($request, [
			'body' => 'required|max:2000'
		]);


		if($request['draft_id']){
			$post = Post::find($request['draft_id']);
		}else{
			$post = new Post();
		}

		// stores data within the posts columns
		$post->title = $request['title'];
		$post->body = $request['body'];
		$post->type = $request['type'];
		$post->published = $request['publish'];

		$message = 'There was an error';

		//Save image
		if ($request->hasFile('image')) {

			$image = $request->file('image');
			$filename = time() . '.' . $image->getClientOriginalExtension(); // Save by timestamp and original file extension
			$location = public_path('images/' . $filename);
			Image::make($image)->resize(800, 400)->save($location); // Resize image to 800 x 400 and stores it

			$post->image = $filename; // Saves path in database
		}

		//Fetch YoutubeID from link

		if(strlen($request['video_link']) > 11)
		{
			if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $request['video_link'], $match))
			{
				$post->video_link = $match[1];
			}

		}

		//save categories

		$categoryStr = $request->input('categories');
		$categories = array_map('trim', explode(',', $categoryStr));
		foreach ($categories as $category) {
			$catModel = Category::firstOrCreate(['name' => $category]);
			$catModel->posts()->save($post);//$post can be the post model or the id that you want to associate with the category

		}



		//Save sources to post
		if ($request['source-1']) {

			$source = new Source();
			$source->link = $request['source-1'];
			$source->post_id = $post->id;

			$source->save();
		}
		if ($request['source-2']) {

			$source = new Source();
			$source->link = $request['source-2'];
			$source->post_id = $post->id;

			$source->save();
		}
		if ($request['source-3']) {

			$source = new Source();
			$source->link = $request['source-3'];
			$source->post_id = $post->id;

			$source->save();
		}
		if($request['draft_id']){
			$post->update();
			$message = 'Draft successfully created!';
		}
		//Saves posts as currently Authenticated user
		elseif ($request->user()->posts()->save($post)) {
			$message = 'Post successfully created!';
		}




		return redirect()->route('dashboard')->with(['message' => $message]);
	}

	public function getDeletePost($post_id)
	{
		$post = Post::where('id', $post_id)->first(); //Just like MySQL "GET post BY id WHERE post = '.$post_id.' 
		if (Auth::user() != $post->user) { // Only allows logged in owners of post to delete specific post
			return redirect()->back();
		}
		$post->delete();
		return redirect()->route('dashboard')->with(['message' => 'Successfully deleted']);
	}

	public function postEditPost(Request $request)
	{ //How does the Request really works?
		$this->validate($request, [
			'body' => 'required'
		]);
		$post = Post::find($request['postId']); //find is similar to WHERE MySQL statement

		/* THIS CREATES INTERNAL ERROR 500 FOR SOME REASON    ---- Laravel 5.2 PHP Build a social network - Updating DB & View
		
		if (Auth::user() != $post->user) { 
			return redirect()->back();
		}
		
		*/
		$post->body = $request['body']; //Change body table WHERE ID = postId
		$post->update(); // MySQL UPDATE

		return response()->json(['new_body' => $post->body], 200); //Returns new body
	}

	public function postLikePost(Request $request)
	{
		$post_id = $request['postId'];
		$is_like = $request['isLike'] === 'true'; //Get's passed as string in request, changed to boolean. 
		$update = false;

		//REDO WITH SMARTER SOLUTION
		if ($is_like == 0) {
			$is_like = -1;
		}

		$post = Post::find($post_id);
		if (!$post) {
			return null;
		}
		$user = Auth::user();
		$like = $user->likes()->where('post_id', $post_id)->first(); //First has to be specified
		if ($like) {
			$already_like = $like->like;
			$update = true;

			//Deletes if it already exists.
			if ($already_like == $is_like) {
				$like->delete();
				return null;
			}
		} else {
			$like = new Like(); //Creates new row for Like in table
		}
		$like->like = $is_like; //Set's whatever $like->like to whatever $request['isLike'] passed.
		$like->user_id = $user->id;
		$like->post_id = $post_id;

		if ($update) {
			$like->update();
		} else {
			$like->save();
		}
		return null;
	}

	public function postCreateComment(Request $request)
	{
		$this->validate($request, [
			'body' => 'required|max:500'
		]);
		$comment = new Comment();
		$comment->body = $request['body']; // stores within the body column
		$comment->post_id = $request['postId'];
		$message = 'There was an error';

		if ($request->user()->comments()->save($comment)) //Saves comments as currently Authenticated user
		{
			$message = 'Comment successfully created!';
		}
		return redirect()->route('dashboard')->with(['message' => $message]);
	}

	public function postShowPost($id)
	{

		$post = Post::find($id); // Kind of like where, but find only search for primary id

		return view('posts')->with('post', $post);
	}

}