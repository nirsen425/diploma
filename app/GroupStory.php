<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupStory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id', 'course_id', 'name', 'year_history'
    ];

    /**
     * Получение Group привязанного к GroupStory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo('App\Group');
    }

    /**
     * Получение Students привязанного к GroupStory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function students()
    {
        return $this->belongsToMany('App\Student', 'student_group_stories');
    }
}
