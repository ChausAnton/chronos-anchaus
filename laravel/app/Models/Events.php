<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_author_id',
        'event_calendar_id',
        'event_content',
        'event_title',
        'event_date',
        'event_category',
    ];


    use HasFactory;
}
