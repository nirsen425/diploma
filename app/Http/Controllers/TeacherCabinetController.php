<?php

namespace App\Http\Controllers;

use App\Teacher;
use Illuminate\Http\Request;

class TeacherCabinetController extends Controller
{
    public function index()
    {
        return view('teacher-profile');
    }
}
