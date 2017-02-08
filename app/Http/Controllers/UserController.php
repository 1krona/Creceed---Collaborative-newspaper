<?php
namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller{

	

	public function postSignUp(Request $request)
	{
	
		// Built in helper method with rules for validation
		$this->validate($request, [
			'email' => 'email|unique:users|required',
			'first_name' => 'required|max:120',
			'password' => 'required|min:4'
		]);
		$email = $request['email'];
		$password = bcrypt($request['password']);
		$first_name = $request['first_name'];
	
		$user = new User();
		$user->email = $email;
		$user->first_name = $first_name;
		$user->password = $password;
		
		$user->save();
		
		Auth::login($user);
	
		return redirect()->route('dashboard');  
	}
	
	public function postSignIn(Request $request){
	
		$this->validate($request, [
			'email' => 'required',
			'password' => 'required'
		]);
		
		//AUTH fetch a helper function named ATTEMPT imported from use Illuminate\Support\Facades\Auth;
		if(Auth::attempt(['email' => $request['email'], 'password' => $request['password']])){
			return redirect()->route('dashboard');
		}
		return redirect()->back(); 	
	}
	
	public function getLogout()
	{
		Auth::logout();
		return redirect()->route('home');
	}
	
	public function getAccount()
	{
		return view('account', ['user' => Auth::user()]); //Returns view('account') and currently logged in user.
	}
	public function postSaveAccount(Request $request) //Request gets data send in when submitting post and store it as an array $requests
	{
		 $this->validate($request, [
			'first_name' => 'required|max:120'
		 ]);
		 
		 $user = Auth::user();
		 $user->first_name = $request['first_name'];
		 $user->update(); //Important! We're UPDATING, not saving.
		 $file = $request->file('image');
		 $filename = $request['first_name'] . '-' . $user->id . '.jpg'; //We don't check any file formats, just adds extension and naming convention
		 
		 if($file){
			Storage::disk('local')->put($filename, File::get($file)); //Don't forget to use Illuminate\Support\Facades\Storage and \File, this gets the facade to call helper methods within Laravel;
		 }
		 return redirect()->route('account');
	}
	public function getUserImage($filename)
	{
		$file = Storage::disk('local')->get($filename); 
		return new Response($file, 200); //We don't return a new view, instead we return the source to where the file is stored.
	}

	public function getUser($user_id){

		$user = User::find($user_id);

		return view('user', ['user' => $user]);
	}
}