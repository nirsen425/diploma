<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentCabinetController extends Controller
{
    public function index()
    {
        return view('student-profile');
    }
}
