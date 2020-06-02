<?php

namespace App\Http\Middleware;

use App\Helpers\Helper;
use Closure;

class CheckStudentAccessForFullTeacherDescription
{
    /**
     * Проверка на то что берет ли преподаватель студентов и берет ли курс этого студента
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        if (Helper::isStudent($user)) {
            $teacherLimitForCurrentYear = $request->teacher->currentLimits()
                ->where('year', '=', Helper::getSchoolYear())->first();
            if (isset($teacherLimitForCurrentYear) and $teacherLimitForCurrentYear->limit > 0) {
                $studentCourse = $user->student()->first()->group()->first()->course_id;
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

                if ($teacherLimitForCurrentYear->$сolumnName) {
                    return $next($request);
                }
            }
        }

        return redirect()->route('index');
    }
}
