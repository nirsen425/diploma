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
}
