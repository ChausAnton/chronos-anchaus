<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController as ControllersCrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Category;

class CategoryCrudController extends ControllersCrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation { destroy as traitDestroy; }

    public function setup() {
        CRUD::setModel(\App\Models\Category::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/category');
        CRUD::setEntityNameStrings('category', 'categories');
    }

    protected function setupListOperation() {
        CRUD::column('id');
        CRUD::setFromDb(); 
    }


    protected function setupCreateOperation() {
        CRUD::field('title');
        CRUD::field('description');
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function store() {
        $validated = request()->validate([
            'title' => 'required|string',
            'description' => 'required|string',
        ]);
        $category = Category::create(request()->all());
        return redirect('/admin/category/' . $category->id . '/show');
    }

    public function update()
    {
        $validated = request()->validate([
            'title' => 'required|string',
            'description' => 'required|string',
        ]);
        $response = $this->traitUpdate();
        return $response;
    }

}
