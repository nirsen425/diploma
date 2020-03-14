<?php

namespace App\Http\Controllers;

use App\Application;
use App\Student;
use App\Teacher;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $application;
    protected $teacher;
    protected $student;

    /**
     * AdminController constructor.
     * @param Application $application
     * @param Teacher $teacher
     * @param Student $student
     */
    public function __construct(Application $application, Teacher $teacher, Student $student)
    {
        $this->application = $application;
        $this->teacher = $teacher;
        $this->student = $student;
    }

    public function showTeacherApplications()
    {
        $teachers = $this->teacher->all();
        return view('admin.teacher-applications', ['teachers' => $teachers]);
    }
}
