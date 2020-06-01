<?php

namespace App;

use App\Helpers\Helper;
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
    public function applications()
    {
        return $this->hasMany('App\Application');
    }

    /**
     * Получение Group привязанного к Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo('App\Group');
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

    /**
     * Возвращает преподавателя по типу заявки, например заявка на практику($typeActivity == 1)
     *
     * @param $typeActivity
     * @return mixed
     */
    public function getTeacherByTypeActivity($typeActivity)
    {
        return $this->applications()->where('type_id', '=', $typeActivity)->first()->teacher()->first();
    }

    public function hasAccessForSendApplicationForTeacher($teacher)
    {
        $teacherLimitForCurrentYear = $teacher->currentLimits()
            ->where('year', '=', Helper::getSchoolYear())->first();
        if (isset($teacherLimitForCurrentYear) and $teacherLimitForCurrentYear->limit > 0) {
            $studentCourse = $this->group()->first()->course;
            if ($studentCourse == 1) {
                $сolumnName = 'first_course';
            }

            if ($studentCourse == 2) {
                $сolumnName = 'second_course';
            }

            if ($studentCourse == 3) {
                $сolumnName = 'third_course';
            }

            if ($studentCourse == 4) {
                $сolumnName = 'fourth_course';
            }

            return $teacherLimitForCurrentYear->$сolumnName;
        }
    }
}
