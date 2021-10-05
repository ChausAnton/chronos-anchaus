<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;


class CategoryController extends Controller
{
    public function index()
    {
        //get Category
        return Category::all();

    }

    
    public function store(Request $request)
    {
        //create a Category
        if(isAdmin(auth()->user()))
            return Category::create($request->all());
        return "only admin can create a new category";
    }

    
    public function show($id)
    {
        //show Category
        return Category::find($id);
    }

    
    public function update(Request $request, $id)
    {
        //update Category
        if(isAdmin(auth()->user())) {
            $Category = Category::find($id);
            $Category->update($request->all());
            return $Category;
        }
        return "only admin can change info about category";
    }


    public function destroy($id)
    {
        if(isAdmin(auth()->user()))
            return Category::destroy($id);
        return "only admin can delete category";
        //delete Category
        
    }
}
