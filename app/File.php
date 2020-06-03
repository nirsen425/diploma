<?php

namespace App;

use Carbon\Carbon;
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
     * Получение даты загрузки файла в нужном формате
     * @param $value
     * @return string
     */
    public function getCreatedAtAttribute($value) {
        return Carbon::parse($value)->timezone('Asia/Yekaterinburg')->format('d.m.Y H:i:s');
    }
}
