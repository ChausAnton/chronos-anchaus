<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController as ControllersCrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Comment;

class CommentsCrudController extends ControllersCrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation { destroy as traitDestroy; }

    public function setup() {
        CRUD::setModel(\App\Models\Comment::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/comment');
        CRUD::setEntityNameStrings('comment', 'comments');
    }

    protected function setupListOperation() {
        CRUD::column('id');
        CRUD::setFromDb(); 
    }


    protected function setupCreateOperation() {
        CRUD::addField([
            'name' => 'post_id',
            'label' => 'post id',
            'type' => 'text'
        ]);
        CRUD::field('content');
        CRUD::field('status');
        CRUD::modifyField('status', [
            'type' => 'enum',
        ]);
    }

    protected function setupUpdateOperation() {
        CRUD::field('status');
        CRUD::modifyField('status', [
            'type' => 'enum',
        ]);
    }

    public function store() {
        $validated = request()->validate([
            'post_id' => 'required|string',
            'content' => 'required|string',
            'status' => 'in:active,inactive',
        ]);

        $credentials = request()->only('content', 'post_id', 'status');
        $credentials['author'] = backpack_user()->login;
        $credentials['user_id'] = backpack_user()->id;

        $comment = Comment::create($credentials);
        return redirect('/admin/comment/' . $comment->id . '/show');
    }

    public function destroy($id) {
        $this->crud->hasAccessOrFail('delete');

        $comment = CRUD::getCurrentEntry();
        $user = \App\Models\User::find($comment->user_id);

        $user->rating -= $comment->rating;
        $user->save();

        return $this->crud->delete($id);
    }

}
