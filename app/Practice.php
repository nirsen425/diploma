<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Practice extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'application_start', 'application_end', 'practice_start', 'practice_end', 'practice_info', 'direction_id', 'course_id'
    ];

    protected $dates = ['application_start', 'application_end', 'practice_start', 'practice_end'];

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

    /**
     * Получение даты старта подачи заявок в нужном формате
     * @param $value
     * @return string
     */
    public function getApplicationStartAttribute($value) {
        return Carbon::parse($value)->format('d.m.Y');
    }

    /**
     * Получение даты конца подачи заявок в нужном формате
     * @param $value
     * @return string
     */
    public function getApplicationEndAttribute($value) {
        return Carbon::parse($value)->format('d.m.Y');
    }

    /**
     * Получение даты начала практики в нужном формате
     * @param $value
     * @return string
     */
    public function getPracticeStartAttribute($value) {
        return Carbon::parse($value)->format('d.m.Y');
    }

    /**
     * Получение даты конца практики в нужном формате
     * @param $value
     * @return string
     */
    public function getPracticeEndAttribute($value) {
        return Carbon::parse($value)->format('d.m.Y');
    }
}
