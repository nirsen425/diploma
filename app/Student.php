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
        'name', 'patronymic', 'surname', 'group_id', 'user_id', 'status', 'personal_number'
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
     * Получение GroupStory, привязанных к Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groupStories()
    {
        return $this->belongsToMany('App\GroupStory', 'student_group_stories', 'student_id', 'group_story_id');
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

//    /**
//     * Возвращает преподавателя по типу заявки, например заявка на практику($typeActivity == 1)
//     *
//     * @param $typeActivity
//     * @return mixed
//     */
//    public function getTeacherByTypeActivity($typeActivity)
//    {
//        return $this->applications()->where('type_id', '=', $typeActivity)->first()->teacher()->first();
//    }

    public function hasAccessForSendApplicationForTeacher($teacher)
    {
        $teacherLimitForCurrentYear = $teacher->currentLimits()
            ->where('year', '=', Helper::getSchoolYear())->first();
        if (isset($teacherLimitForCurrentYear) and $teacherLimitForCurrentYear->limit > 0) {
            $studentCourse = $this->group()->first()->course_id;
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
