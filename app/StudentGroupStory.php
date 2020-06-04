<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentGroupStory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id', 'group_story_id'
    ];
}
