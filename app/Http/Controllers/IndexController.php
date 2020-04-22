<?php

namespace App\Http\Controllers;

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
        return view('teacher-list', ['teachers' => $teachers, 'currentYear' => Carbon::now()->year]);
    }

    /**
     * Показывает страницу преподавателя
     *
     * @param Teacher $teacher
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showTeacher(Teacher $teacher)
    {
        return view('teacher', ['teacher' => $teacher, 'currentYear' => Carbon::now()->year]);
    }
}
