<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Direction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'direction', 'direction_name'
    ];

    /**
     * Получение Groups, привязанных к Direction
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groups()
    {
        return $this->hasMany('App\Group');
    }

    /**
     * Получение Practices, привязанных к Direction
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function practice()
    {
        return $this->hasMany('App\Practice');
    }

    /**
     * Получение Files, привязанных к Directions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function files()
    {
        return $this->belongsToMany(
            'App\File',
            'file_direction_course',
            'direction_id',
            'file_id'
        )->withPivot('course_id')->withTimestamps();
    }

    /**
     * Получение Courses, привязанных к Directions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    /*public function courses()
    {
        return $this->belongsToMany('App\Course', 'file_direction_course', 'direction_id', 'course_id')->withTimestamps();
    }*/
}
