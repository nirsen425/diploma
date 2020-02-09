<?php

namespace App\Http\Controllers;

use App\Teacher;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Показывает список преподавателей
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $teachers = Teacher::all();
        return view('teacher-list', ['teachers' => $teachers]);
    }

    /**
     * Показывает страницу преподавателя
     *
     * @param Teacher $teacher
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showTeacher(Teacher $teacher)
    {
        return view('teacher', ['teacher' => $teacher]);
    }
}
