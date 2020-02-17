<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'patronymic', 'surname', 'student_ticket'
    ];

    /**
     * Получение User привязанного к Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Получение Application привязанного к Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function application()
    {
        return $this->hasMany('App\Application');
    }

    /**
     * Возвращает ФИО
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->name . " " . $this->patronymic . " " . $this->surname;
    }

    public function getTeacherByTypeActivity($typeActivity)
    {
        return $this->application()->where('type_id', '=', $typeActivity)->first()->teacher()->first();
    }
}
