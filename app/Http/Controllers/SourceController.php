<?php
namespace App\Http\Controllers;

use App\Like;
use App\Source;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;




class SourceController extends Controller{


    public function sourceLikeSource(Request $request)
    {
        $source_id = $request['sourceId'];
        $post_id = $request['postId'];
        $is_like = $request['isLike'] === 'true'; //Get's passed as string in request, changed to boolean.
        $update = false;

        //REDO WITH SMARTER SOLUTION
        if($is_like == 0){
            $is_like = -1;
        }

        $source = Source::find($source_id);
        if(!$source){
            return null;
        }
        $user = Auth::user();
        $like = $user->likes()->where('source_id', $source_id)->first(); //First has to be specified
        if($like){
            $already_like = $like->like;
            $update = true;

            //Deletes if it already exists.
            if($already_like == $is_like){
                $like->delete();
                return null;
            }
        } else {
            $like = new Like(); //Creates new row for Like in table
        }
        $like->like = $is_like; //Set's whatever $like->like to whatever $request['isLike'] passed.
        $like->user_id = $user->id;
        $like->source_id = $source_id;
        $like->post_id = $post_id;

        if($update){
            $like->update();
        }else{
            $like->save();
        }
        return null;
    }

}