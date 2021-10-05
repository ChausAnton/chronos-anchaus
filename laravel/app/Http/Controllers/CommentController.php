<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        //get Comments
        if($request->header('filter')) {
            if(isAdmin(auth()->user())) 
                return Comment::all()->where("post_id", $request->header('post_id'));
            return getOnlyAtiveComments(NULL, $request->header('post_id'));
        }

        if(isAdmin(auth()->user())) 
            return Comment::all();
        return getOnlyAtiveComments(NULL, NULL);
    }

    public function store(Request $request)
    {

        $validated =  $request->validate([
            'post_id'=> 'required',
            'content'=> 'required|string'
        ]);
        if(auth()->user()) {
            $data = [
                'author' => auth()->user()->login,
                'user_id' => auth()->user()->id,
                'post_id' => $validated ['post_id'],
                'content' => $validated ['content'],
                'rating' => 0,
                'status' => 'active'
            ];
            return Comment::create($data);
        }
    }

   
    public function show($id)
    {
        //show Comment
        if(isAdmin(auth()->user())) 
            return Comment::find($id);
        return getOnlyAtiveComments($id, NULL);
    }

   
    public function update(Request $request, $id)
    {
        //update Comment
        $Comment = Comment::find($id);
        if(!isAdmin(auth()->user()) && !isUser(JWTAuth::getToken(), getUserByLogin($Comment->author)->id)) {
            return "only admin and author can change comment's data";
        }

        if(isAdmin(auth()->user())) {
            if(isset($request['status'])) {
                $Comment->status = $request['status'];
                $Comment->save();
            }
           return $Comment;
        }
        if(isset($request['content'])) {
            $Comment->content = $request['content'];
            $Comment->save();
        }
        return $Comment;
    }

    
    public function destroy($id)
    {
        $post = Comment::find($id);
        if(!isAdmin(auth()->user()) && !isUser(JWTAuth::getToken(), $post->user_id)) {
            return "only admin and author can delete comment";
        }
        return Comment::destroy($id);
        //delete Comment
    }
}
