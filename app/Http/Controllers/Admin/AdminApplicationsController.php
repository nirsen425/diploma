<?php

namespace App\Http\Controllers\Admin;

use App\Application;
use App\Student;
use App\Teacher;
use App\TeacherLimit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminApplicationsController extends Controller
{
    protected $application;
    protected $teacher;
    protected $student;
    protected $teacherLimit;

    /**
     * AdminController constructor.
     * @param Application $application
     * @param Teacher $teacher
     * @param Student $student
     */
    public function __construct(Application $application, Teacher $teacher, Student $student, TeacherLimit $teacherLimit)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->application = $application;
        $this->teacher = $teacher;
        $this->student = $student;
        $this->teacherLimit = $teacherLimit;
    }

    public function showTeacherApplications()
    {
        $teachers = $this->teacher->all();
        return view('admin.teacher-applications', ['teachers' => $teachers]);
    }

    public function showTeacherLimitsPage($year)
    {
        $teachers = $this->teacher->all();
        $limitYears = $this->teacherLimit->select('year')->distinct()->orderBy('year')->get();
        $yearExist = false;
        foreach($limitYears as $limitYear) {
            if ($limitYear->year == $year) $yearExist = true;
        }

        return view('admin.teacher-limits', ['teachers' => $teachers, 'year' => $year, 'limitYears' => $limitYears, 'yearExist' => $yearExist]);
    }

    public function setLimits(Request $request)
    {
        $teacherLimitsArray = $request->teacherLimitsArray;

        foreach($teacherLimitsArray as $teacherLimitArray) {
            $teacherLimitId = $teacherLimitArray['teacher_id'];
            $teacherLimitYear = $teacherLimitArray['year'];
            if ($this->teacherLimit->teacherLimitExist($teacherLimitId, $teacherLimitYear)) {
                $existTeacherLimit = $this->teacherLimit->where([
                    ['teacher_id', '=', $teacherLimitId],
                    ['year', '=', $teacherLimitYear]
                ])->first();
                $existTeacherLimit->update($teacherLimitArray);
            } else {
                $this->teacherLimit->create($teacherLimitArray);
            }
        }

        return "true";
    }
}
