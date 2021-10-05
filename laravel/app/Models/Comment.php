<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use CrudTrait;

    protected $fillable = [
        'author',
        'status',
        'user_id',
        'post_id',
        'content',
        'rating'
    ];

}
