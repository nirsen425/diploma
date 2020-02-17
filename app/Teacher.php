<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'patronymic', 'surname', 'short_description', 'full_description', 'photo', 'show'
    ];

    /**
     * Получение User привязанного к Teacher
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Получение Applications привязанных к Teacher
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function applications()
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
}
