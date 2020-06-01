<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Course;
use App\Direction;
use App\Practice;

class AdminPracticeController extends Controller
{
    protected $direction;
    protected $course;
    protected $practice;

    public function __construct(Direction $direction, Course $course, Practice $practice)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->direction = $direction;
        $this->course = $course;
        $this->practice = $practice;
    }

    public function index($selectedDirectionId, $selectedCourseId = null)
    {
        if (isset($selectedDirectionId))
        {
            if (isset($selectedCourseId))
            {
                $practice = $this->practice->where([
                    ['direction_id', '=', $selectedDirectionId],
                    ['course_id', '=', $selectedCourseId]
                ])->first();
                $data['practice'] = $practice;
            }
        }

        $data['selectedDirectionId'] = $selectedDirectionId;
        $data['selectedCourseId'] = $selectedCourseId;
        $data['directions'] = $this->direction->get();
        $data['courses'] = $this->course->get();

        return view('admin.practice-info', $data);
    }

    public function edit(Request $request, $directionId, $courseId)
    {
        $practiceData = [
            'application_start' => $request['application_start'],
            'application_end' => $request['application_end'],
            'practice_start' => $request['practice_start'],
            'practice_end' => $request['practice_end'],
            'practice_info' => $request['practice_info'],
        ];

        $practice = $this->practice->where([
            ['direction_id', '=', $directionId],
            ['course_id', '=', $courseId]
        ])->first();

        $practice->update($practiceData);

        return back()->with('status', 'Информация успешно изменена');
    }
}
