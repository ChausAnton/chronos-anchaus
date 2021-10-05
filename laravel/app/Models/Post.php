<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use CrudTrait;

    protected $fillable = [
        'author',
        'author_id',
        'title',
        'content',
        'likes',
        'status',
        'categories'
    ];
}
