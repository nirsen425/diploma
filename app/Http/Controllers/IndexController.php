<?php

namespace App\Http\Controllers;

use App\Teacher;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        return view('teacher-list');
    }

    public function showTeacher(Teacher $teacher)
    {
        return view('teacher', ['teacher' => $teacher]);
    }
}
