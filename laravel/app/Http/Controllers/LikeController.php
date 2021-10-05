<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use DB;

class LikeController extends Controller
{
    public function index()
    {
        //get Likes
        return Like::all();
    }

    public function store(Request $request)
    {
        if(auth()->user()) {//likes
            if($request->post_id)
                $like = DB::select("select * from likes where user_id = " . auth()->user()->id . " and post_id = $request->post_id;");
            else
                $like = DB::select("select * from likes where user_id = " . auth()->user()->id . " and comment_id = $request->comment_id;");
            if(!$like) {
                changeRating($request->post_id, $request->comment_id, $request->input('type'));
                $data = [
                    'author' => auth()->user()->login,
                    'user_id' => auth()->user()->id,
                    'post_id' => $request->input('post_id'),
                    'comment_id' => $request->input('comment_id'),
                    'type' => $request->input('type'),
                ];
                
                return Like::create($data);
            }
            elseif($like && strcmp($like[0]->type, $request['type']) != 0) {
                changeRating($request->post_id, $request->comment_id, $request->input('type'));
                if($request->post_id)
                    DB::delete("delete from likes where user_id = " . auth()->user()->id . " and post_id = $request->post_id;");
                else 
                    DB::delete("delete from likes where user_id = " . auth()->user()->id . " and comment_id = $request->comment_id;");
                $data = [
                    'author' => auth()->user()->login,
                    'user_id' => auth()->user()->id,
                    'post_id' => $request->input('post_id'),
                    'comment_id' => $request->input('comment_id'),
                    'type' => $request->input('type'),
                ];
                return Like::create($data);
            }
            return "error";
        }
        return "only logged user can rated";
    }


    public function show($id)
    {
        //show Like
        return Like::find($id);
    }

}
