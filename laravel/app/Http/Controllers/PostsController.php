<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Tymon\JWTAuth\Facades\JWTAuth;
use DB;

//////////////////////
function getPostsWithCategory($all, Request $request) {
    $CategorySub = New CategorySubTableController();
    if($all) {
        $postData = applySortingFiltersAdmin(Post::all(), $request);
        foreach($postData as $post) {
            $post->categories = $CategorySub->getCategoriesForPost($post->id);
        }
        return $postData;
    }
    $postData = applySortingFiltersUser(Post::all(), $request);
    if(is_string($postData) == 1)
        return $postData;
    foreach($postData as $post) {
        $post->categories = $CategorySub->getCategoriesForPost($post->id);
    }
    return $postData;
    
}// move to helpers

function getOnePostWithCategory($admin, $id) {
    $CategorySub = New CategorySubTableController();
    if($admin) {
        $postData = DB::select("select * from posts where id = $id;")->first();
        if($postData)
            $postData->categories = $CategorySub->getCategoriesForPost($id);
        return $postData;
    }
    $postData = getOnlyAtivePosts($id);
    if($postData)
        $postData[0]->categories = $CategorySub->getCategoriesForPost($id);
    return $postData;
}// move to helpers
/////////////////////////



class PostsController extends Controller
{
    public function index(Request $request)
    {
        //get Posts
        if(isAdmin(auth()->user())) {
            return getPostsWithCategory(true, $request);
        }

        return getPostsWithCategory(false, $request);
        
    }

    
    public function store(Request $request)
    {
        if(!auth()->user()) {
            return "only logged users can create posts";
        }

        $validated =  $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'category_id' => 'required'
        ]);

        $data = [
            'author' => auth()->user()->login,
            'author_id' => auth()->user()->id,
            'title' => $validated['title'],
            'content' => $validated['content'],
            'likes' => 0,
            'status' => 'active'
        ];

        $post = Post::create($data);
        $CategorySub = New CategorySubTableController();
        $CategorySub->addCategory($request['category_id'], $post->id);
        return $post;
    }


    public function show($id)
    {
        //show Post
        if(isAdmin(auth()->user()))
            return getOnePostWithCategory(true, $id);
        return getOnePostWithCategory(false, $id);
    }   

    
    public function update(Request $request, $id)
    {
        //update Post
        $post = Post::find($id);
        if(!isAdmin(auth()->user()) && !isUser(JWTAuth::getToken(), getUserByLogin($post->author)->id)) {
            return "only admin and author of post can change post's data";
        }

        if(isset($request['category_id'])) {
            $CategorySub = New CategorySubTableController();
            $CategorySub->addCategory($request['category_id'], $post->id);
        }

        if(strcmp($request['status'], 'inactive') == 0) {
            if(isset($request['status'])) {
                $post->status = $request['status'];
                $post->save();
            }
        }
        elseif(isAdmin(auth()->user())) {
            if(isset($request['status'])) {
                $post->status = $request['status'];
                $post->save();
            }
           return $post;
        }


        $data = [
            'title' => ($request->input('title') ? $request->input('title') : $post->title),
            'content' => ($request->input('content') ? $request->input('content') : $post->content),
        ];
        
        $post->update($data);
        return $post;
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        if(!isAdmin(auth()->user()) && !isUser(JWTAuth::getToken(), getUserByLogin($post->author)->id)) {
            return "only admin and author can delete post";
        }
        return Post::destroy($id);
        //delete Post
        
    }
}
