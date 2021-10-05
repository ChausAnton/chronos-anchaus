<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController as ControllersCrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserCrudController extends ControllersCrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation { destroy as traitDestroy; }

    protected function setupShowOperation() {
        CRUD::column('id');
        CRUD::column('login');
        CRUD::column('real_name');
        CRUD::column('rating');
        CRUD::column('email');
        CRUD::column('role');
        CRUD::addColumn([
            'name' => 'image_path',
            'label' => 'Avatar',
            'type' => 'image',
            'width' => '150px',
            'height' => '150px'
        ]);
    }

    public function setup() {
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('User', 'Users');
    }

    protected function setupListOperation() {
        CRUD::column('id');
        CRUD::setFromDb(); 
    }


    protected function setupCreateOperation() {
        CRUD::field('login');
        CRUD::field('real_name');
        CRUD::field('email');
        CRUD::field('password');
        CRUD::field('password_confirmation');
        CRUD::field('role');
        CRUD::addField([
            'name' => 'image_path',
            'label' => 'Avatar',
            'type' => 'image',
            'crop' => true,
            'aspect_ratio' => 1,
        ]);
        CRUD::modifyField('role', [
            'type' => 'enum',
        ]);
    }

    protected function setupUpdateOperation()
    {
        CRUD::field('role');
        CRUD::modifyField('role', [
            'type' => 'enum',
        ]);
    }

    public function store() {
        $credentials = request()->only([
            'login', 
            'password', 
            'password_confirmation', 
            'email', 
            'role', 
            'full_name', 
            'image_path'
        ]);

        $validated = request()->validate([
            'login'=> 'required|string|unique:users,login',
            'real_name'=> 'string',
            'email'=> 'required|email|unique:users,email',
            'password'=> 'required|confirmed|min:4',
            'password_confirmation'=> 'required',
            'role' => 'in:admin,user'
        ]);

        $credentials['password'] = Hash::make($validated['password']);
        
        $image_data = '';
        if (isset($credentials['image_path'])) {
            $image_data = $credentials['image_path'];
            unset($credentials['image_path']);
        }

        $user = User::create($validated);

        if (isset($credentials['image_path'])) {
            $avatar_data = explode(';', $image_data);
            $avatar_data[1] = explode(',', $avatar_data[1])[1];
            $image_data = 'avatars/' . $user->id . '.png';
            $file = fopen($image_data, "w");
            fwrite($file, base64_decode($avatar_data[1]));
            fclose($file);

            $user->image_path = $image_data;
            $user->save();
        }


        return redirect('/admin/user/' . $user->id . '/show');
    }

    public function update()
    {
        request()->validate([
            'role' => 'in:admin,user'
        ]);

        $user = CRUD::getCurrentEntry();
        $user->update(['role' => request()->input('role')]);;
        $response = $this->traitUpdate();
        return $response;
    }

}
