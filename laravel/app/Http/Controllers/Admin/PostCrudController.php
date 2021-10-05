<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController as ControllersCrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Post;
use App\Http\Controllers\CategorySubTableController;

class PostCrudController extends ControllersCrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation { destroy as traitDestroy; }

    public function setup() {
        CRUD::setModel(\App\Models\Post::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/post');
        CRUD::setEntityNameStrings('Post', 'Posts');
    }

    protected function setupShowOperation() {
        $post = CRUD::getCurrentEntry();
        $CategorySub = New CategorySubTableController();
        $categories = $CategorySub->getCategoriesForPost($post->id);
        $i = 0;
        $post->categories = '[';
        foreach($categories as $category) {
            if($i > 0)
            $post->categories .= ', ';
            $post->categories .= $category->category_id;
            $i++;
        }
        $post->categories .= ']';

        CRUD::column('id');
        CRUD::column('author');
        CRUD::column('author_id');
        CRUD::column('title');
        CRUD::column('content');
        CRUD::column('likes');
        CRUD::column('status');
        CRUD::column('categories');

    }

    protected function setupListOperation() {
        CRUD::column('id');
        CRUD::column('author');
        CRUD::column('title');
        CRUD::column('likes');
        CRUD::column('status');

    }


    protected function setupCreateOperation() {
        CRUD::field('title');
        CRUD::field('content');
        CRUD::field('status');
        CRUD::modifyField('status', [
            'type' => 'enum',
        ]);
        CRUD::addField([
            'name' => 'categoryID',
            'label' => 'categories id',
            'type' => 'text'
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function store() {
        
        $validated = request()->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'status' => 'in:active,inactive',
            'categoryID' => 'required|string',
        ]);

        $data = [
            'author' => backpack_user()->login,
            'author_id' => backpack_user()->id,
            'title' => $validated['title'],
            'content' => $validated['content'],
            'likes' => 0,
            'status' => $validated['status']
        ];

        $post = Post::create($data);
        $CategorySub = New CategorySubTableController();
        $CategorySub->addCategory(explode(',', request()->categoryID), $post->id);

        return redirect('/admin/post/' . $post->id . '/show');
    }

    public function update()
    {
        $post = CRUD::getCurrentEntry();
    
        if(isset(request()->categoryID)) {
            $CategorySub = New CategorySubTableController();
            $CategorySub->addCategory(explode(',', request()->categoryID), $post->id);
        }

        if(isset($request['status'])) {
            $post->status = $request['status'];
            $post->save();
        }

        if($post->author_id == backpack_user()->id) {
            $data = [
                'title' => (request()->input('title') ? request()->input('title') : $post->title),
                'content' => (request()->input('content') ? request()->input('content') : $post->content),
            ];
            
            $post->update($data);
        }
        
        return redirect('/admin/post/' . $post->id . '/show');
    }

}
