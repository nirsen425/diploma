<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Practice extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'practice_start', 'practice_end', 'practice_info', 'direction_id', 'course_id'
    ];

    /**
     * Получение Course, привязанного к Practice
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo('App\Course');
    }

    /**
     * Получение Direction, привязанного к Practice
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function direction()
    {
        return $this->belongsTo('App\Direction');
    }
}
