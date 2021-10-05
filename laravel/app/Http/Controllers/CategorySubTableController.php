<?php

namespace App\Http\Controllers;

use DB;
use App\Models\CategorySubTabel;


class CategorySubTableController extends Controller
{
   
    public function addCategory($CategoryJson, $postID)
    {
        DB::delete("delete from category_sub where post_id = $postID;");
        foreach($CategoryJson as $cat) {
            $data = [
                'post_id' => $postID,
                'category_id' => $cat
            ];
            CategorySubTabel::create($data);
        }
        return true;
    }

    public function getCategoriesForPost($postID) {
        return DB::select("select * from category_sub where post_id = $postID;");
    }
    
}
