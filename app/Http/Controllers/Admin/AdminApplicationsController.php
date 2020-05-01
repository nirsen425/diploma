<?php

namespace App\Http\Controllers\Admin;

use App\Application;
use App\ApplicationStatus;
use App\GroupStory;
use App\Student;
use App\Teacher;
use App\TeacherLimit;
use App\Group;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminApplicationsController extends Controller
{
    protected $application;
    protected $teacher;
    protected $student;
    protected $teacherLimit;
    protected $groupStory;
    protected $applicationStatus;

    /**
     * AdminController constructor.
     * @param Application $application
     * @param Teacher $teacher
     * @param Student $student
     */
    public function __construct(Application $application, Teacher $teacher, Student $student,
                                TeacherLimit $teacherLimit, GroupStory $groupStory,
                                ApplicationStatus $applicationStatus)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->application = $application;
        $this->teacher = $teacher;
        $this->student = $student;
        $this->teacherLimit = $teacherLimit;
        $this->groupStory = $groupStory;
        $this->applicationStatus = $applicationStatus;
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

    public function showStudentApplications($historyYear, $groupStoryId = null)
    {
        $yearsGroupStories = $this->groupStory->select('year_history')->distinct()->orderBy('year_history')->get();
        if (isset($groupStoryId)) {
            $currentGroupStory = $this->groupStory->where('id', '=', $groupStoryId)->first();
            $parameters['currentGroupStory'] = $currentGroupStory;
        }

        $groupStories = $this->groupStory->where('year_history', '=', $historyYear)->get();
        $parameters['groupStories'] = $groupStories;
        $parameters['historyYear'] = $historyYear;
        $parameters['yearsGroupStories'] = $yearsGroupStories;
        $parameters['teacherModel'] = $this->teacher;
        $parameters['applicationStatuses'] = $this->applicationStatus->all();

        return view('admin.student-applications', $parameters);
    }

    public function changeOrCreateApplication(Request $request)
    {
        $applictionDataArray = $request->applictionDataArray;

        $lastApplication = $this->application->where([
            ['year', '=', $applictionDataArray['year']],
            ['student_id', '=', $applictionDataArray['studentId']]
        ])->get()->last();

        if (isset($lastApplication)) {
            $parameters = [
                'teacher_id' => $applictionDataArray['teacherId'],
                'status_id' => $applictionDataArray['statusId'],
                'reply_datetime' => date("Y-m-d H:i:s")
            ];

            if ($applictionDataArray['statusId'] == 1) {
                $parameters['reply_datetime'] = null;
            }

            $lastApplication->update($parameters);
        } else {
            $parameters = [
                'year' => $applictionDataArray['year'],
                'student_id' => $applictionDataArray['studentId'],
                'teacher_id' => $applictionDataArray['teacherId'],
                'type_id' => 1,
                'status_id' => $applictionDataArray['statusId'],
                'reply_datetime' => date("Y-m-d H:i:s")
            ];

            if ($applictionDataArray['statusId'] == 1) {
                $parameters['reply_datetime'] = null;
            }

            $this->application->create($parameters);
        }

        return "true" ;
    }
}
