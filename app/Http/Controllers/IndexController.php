<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Teacher;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    private $teacher;

    /**
     * IndexController constructor.
     */
    public function __construct(Teacher $teacher)
    {
        $this->middleware('auth');
        // Проверка пользователя на студента
        $this->middleware('student');
        $this->teacher = $teacher;
    }

    /**
     * Показывает список преподавателей которые могут принимать заявки от текущего авторизованного студента
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Authenticatable $user)
    {
        $teachers = $this->teacher->getTeachersByCourseForCurrentYear($user->student()->first()->group()->value('course'));
        return view('teacher-list', ['teachers' => $teachers, 'currentYear' => Helper::getSchoolYear()]);
    }

    /**
     * Показывает страницу преподавателя
     *
     * @param Teacher $teacher
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showTeacher(Teacher $teacher, Authenticatable $user)
    {
        // Получение подтвержденной заявки для студента зашедшего на страницу, в дальнейшем нужную для проверки
        // возможности отправлять новые заявки
        $confirmApplication = $user->student()->first()->applications()->where([
            ['type_id', '=', 1],
            ['year', '=', Helper::getSchoolYear()],
            ['status_id', '=', 2]
        ])->first();

        return view('teacher', ['teacher' => $teacher, 'currentYear' => Helper::getSchoolYear(), 'confirmApplication' => $confirmApplication]);
    }
}
