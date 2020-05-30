<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course'
    ];

    /**
     * Получение Groups, привязанных к Course
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groups()
    {
        return $this->hasMany('App\Group');
    }

    /**
     * Получение Practices, привязанных к Course
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function practices()
    {
        return $this->hasMany('App\Practice');
    }

    /**
     * Получение Directions, привязанных к Courses.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function directions()
    {
        return $this->belongsToMany('App\Direction', 'file_direction_course', 'course_id', 'direction_id')->withTimestamps();;
    }

    /**
     * Получение Files, привязанных к Groups.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function files()
    {
        return $this->belongsToMany('App\File', 'file_direction_course', 'course_id', 'file_id')->withTimestamps();;
    }
}
