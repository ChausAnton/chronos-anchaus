<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'calendar_author_id',
        'calendar_title',
    ]; 

    use HasFactory;
}
