<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use CrudTrait;

    protected $fillable = [
        'author',
        'user_id',
        'post_id',
        'comment_id',
        'type'
    ];
    
}
