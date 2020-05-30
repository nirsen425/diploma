<?php

namespace App;

use App\Helpers\Helper;
use Carbon\Carbon;
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
     * Получение TeacherLimits привязанных к Teacher
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function currentLimits()
    {
        return $this->hasMany('App\TeacherLimit');
    }

    /**
     * Возвращает количество студентов, которое он может взять на практику текущего года
     *
     * @return int|mixed
     */
    public function currentYearPracticeLimits()
    {
        return $this->currentLimits()->where('year', '=', Helper::getSchoolYear())->value('limit');
    }

    /**
     * Возвращает количество свободных мест у преподавателя на текущий учебный год
     *
     * @return int|mixed
     */
    public function countFreePracticePlaces()
    {
        $countFreePracticePlaces = $this->currentYearPracticeLimits() - $this->countPracticeApplications();
        return ($countFreePracticePlaces >= 0) ? $countFreePracticePlaces : 0;
    }

    /**
     * Возвращает количество подтвержденных заявок преподавателем
     *
     * @return int
     */
    public function countPracticeApplications()
    {
        return $this->applications()->where([
            ['type_id', '=', 1],
            ['status_id', '=', 2],
            ['year', '=', Helper::getSchoolYear()]
        ])->get()->count();
    }

    /**
     * Возвращает ФИО
     *
     * @return string
     */
    public function getFullName()
    {
        //return $this->name . " " . $this->patronymic . " " . $this->surname;
        return $this->surname . " " . $this->name . " " . $this->patronymic;
    }

    /**
     * Возвращает учителей которые берут хотя бы одного студета курса $course в текущем году
     *
     * @param $course
     * @return array
     */
    public function getTeachersByCourseForCurrentYear($course)
    {
        if ($course == 1) {
            $сolumnName = 'first_course';
        }

        if ($course == 2) {
            $сolumnName = 'second_course';
        }

        if ($course == 3) {
            $сolumnName = 'third_course';
        }

        if ($course == 4) {
            $сolumnName = 'fourth_course';
        }

        $teacherLimits = TeacherLimit::where([
            [$сolumnName, '=', true],
            ['limit', '>', 0],
            ['year', '=', Helper::getSchoolYear()]
        ])->get();

        foreach($teacherLimits as $teacherLimit) {
            $teachers[] = $teacherLimit->teacher()->first();
        }
        if (isset($teachers)) {
            return $teachers;
        }

        return false;
    }
}
