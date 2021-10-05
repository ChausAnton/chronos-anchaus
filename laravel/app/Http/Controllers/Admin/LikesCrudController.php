<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController as ControllersCrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Like;
use DB;

class LikesCrudController extends ControllersCrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation { destroy as traitDestroy; }

    public function setup() {
        CRUD::setModel(\App\Models\Like::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/like');
        CRUD::setEntityNameStrings('Like', 'Likes');
    }

    protected function setupShowOperation() {
        CRUD::removeButton('update');
    }

    protected function setupListOperation() {
        CRUD::removeButton('update');
        CRUD::column('id');
        CRUD::setFromDb(); 
    }


    protected function setupCreateOperation() {
        CRUD::removeButton('update');
        CRUD::field('type');
        CRUD::addField([
            'name' => 'postId',
            'label' => 'post id',
            'type' => 'text'
        ]);
        CRUD::addField([
            'name' => 'commentId',
            'label' => 'comment id',
            'type' => 'text'
        ]);
        CRUD::modifyField('type', [
            'type' => 'enum',
        ]);

    }

    protected function setupUpdateOperation() {
        CRUD::removeButton('update');
    }

    public function store()
    {
        $validated = request()->validate([
            'postId' => 'required_without:commentId',
            'commentId' => 'required_without:postId',
            'Type' => 'in:like,dislike',
        ]);

        if(request()->postId)
            $like = DB::select("select * from likes where user_id = " . backpack_user()->id . " and post_id = " . request()->postId . ";");
        else
            $like = DB::select("select * from likes where user_id = " . backpack_user()->id . " and comment_id = " . request()->commentId . ";");
        if(!$like) {
            changeRating(request()->postId, request()->commentId, request()->input('type'));
            $data = [
                'author' => backpack_user()->login,
                'user_id' => backpack_user()->id,
                'post_id' => request()->input('postId'),
                'comment_id' => request()->input('ccommentId'),
                'type' => request()->input('type'),
            ];
            
            $like_res = Like::create($data);
            return redirect('/admin/like/' . $like_res->id . '/show');
        }
        elseif($like && strcmp($like[0]->type, request()['type']) != 0) {
            changeRating(request()->postId, request()->commentId, request()->input('type'));
            if(request()->postId)
                DB::delete("delete from likes where user_id = " . backpack_user()->id . " and post_id = " . request()->postId . ";");
            else 
                DB::delete("delete from likes where user_id = " . backpack_user()->id . " and comment_id = " . request()->commentId . ";");
            $data = [
                'author' => backpack_user()->login,
                'user_id' => backpack_user()->id,
                'post_id' => request()->input('postId'),
                'comment_id' => request()->input('commentId'),
                'type' => request()->input('type'),
            ];
            $like_res = Like::create($data);
            return redirect('/admin/like/' . $like_res->id . '/show');
        }
        return redirect('/admin/like/');
    }
}
