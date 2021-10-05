<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorySubTabel extends Model
{
    public $table = "category_sub";

    protected $fillable = [
        'post_id',
        'category_id'
    ];

    protected $casts = [
        'post_id' => 'integer',
        'category_id' => 'integer'
    ];
}
