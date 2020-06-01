<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'extension', 'path'
    ];

    /**
     * Получение Directions, привязанных к Files.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function directions()
    {
        return $this->belongsToMany(
            'App\Direction',
            'file_direction_course',
            'file_id',
            'direction_id'
        )->withPivot('course_id')->withTimestamps();;
    }

    /**
     * Получение Courses, привязанных к Files.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    /*public function courses()
    {
        return $this->belongsToMany('App\Course', 'file_direction_course', 'file_id', 'course_id');
    }*/

    /**
     * Accessor возвращающий время загрузки файла в виде timestamp
     *
     * @param $value
     * @return int timestamp
     */
    public function getCreatedAtAttribute($value)
    {
        $date = $this->asDateTime($value);
        return $date->getTimestamp();
    }
}
