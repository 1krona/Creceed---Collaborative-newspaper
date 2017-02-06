<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


//['middleware' => ['web'] checks if csrf filter is set

Route::group(['middleware' => ['web']], function() {



});
/*
Route::get('/', function () {
	return view('welcome');
})->name('home');
*/
Route::get('/', [
	'uses' => 'PostController@getHome',
	'as' => 'home'
]);

/* {type} specify posts to show */
Route::get('/type/{type}', [
	'uses' => 'PostController@getHomeByType',
	'as' => 'home.type'
]);


Route::get('/login-modal', function () {
	return view('login-modal');
})->name('login-modal');

Route::post('/signup', [
	'uses' => 'UserController@postSignUp', //Function that gets executed when "signup" request is sent.
	'as' => 'signup'
]);

Route::post('/signin', [
	'uses' => 'UserController@postSignIn',
	'as' => 'signin'
]);

Route::get('/dashboard/', [
	'uses' => 'PostController@getDashboard',
	'as' => 'dashboard',
	'middleware' => 'auth'
]);

Route::post('createpost', [
	'uses' => 'PostController@postCreatePost',
	'as' => 'post.create',
	'middleware' => 'auth'
]);

Route::get('/delete-post/{post_id}', [
	'uses' => 'PostController@getDeletePost',
	'as' => 'post.delete',
	'middleware' => 'auth'
]);

Route::get('/logout',[
	'uses' => 'UserController@getLogout',
	'as' => 'logout'
]);

Route::get('/account', [
	'uses' => 'UserController@getAccount',
	'as' => 'account'
]);

Route::post('/updateaccount', [
	'uses' => 'UserController@postSaveAccount',
	'as' => 'account.save'
]);

Route::get('/userImage/{filename}',[
	'uses' => 'UserController@getUserImage',
	'as' => 'account.image'
]);

Route::post('/edit', [

	'uses' => 'PostController@postEditPost',
	'as' => 'edit'

]);
Route::post('/like', [
	'uses' => 'PostController@postLikePost',
	'as' => 'like'
]);
Route::post('createcomment', [
	'uses' => 'PostController@postCreateComment',
	'as' => 'comment.create',
	'middleware' => 'auth'
]);
Route::post('/likesource', [
	'uses' => 'SourceController@sourceLikeSource',
	'as' => 'likesource'
]);
Route::get('/posts/{id}', [
	'uses' => 'PostController@postShowPost'
]);

Route::get('/dashboard/{draft_id}', [
	'uses' => 'PostController@getDashboardWithDraft',
	'as' => 'get.draft',
	'middleware' => 'auth'
]);