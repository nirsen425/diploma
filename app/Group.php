<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    /**
     * Получение Students привязанных к Groups
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function students()
    {
        return $this->hasMany('App\Student');
    }

    /**
     * Получение GroupStories, привязанных к Group
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groupStories()
    {
        return $this->hasMany('App\GroupStory');
    }

    /**
     * Получение Course, привязанного к Group
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo('App\Course');
    }

    /**
     * Получение Direction, привязанного к Group
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function direction()
    {
        return $this->belongsTo('App\Direction');
    }
}
