<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherLimit extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'teacher_id', 'year', 'limit', 'first_course', 'second_course', 'third_course', 'fourth_course'
    ];

    public function teacherLimitExist($id, $year) {
        return $this->where([
            ['teacher_id', '=', $id],
            ['year', '=', $year]
        ])->first();
    }
}
