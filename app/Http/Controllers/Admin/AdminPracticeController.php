<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
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

    /**
     * Вывод информации и сроков по направлению-курсу
     * @param $selectedDirectionId
     * @param null $selectedCourseId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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
                $data['practice_info'] = $practice->practice_info;
                $data['application_start'] = Carbon::parse($practice->application_start)->format('Y-m-d');
                $data['application_end'] = Carbon::parse($practice->application_end)->format('Y-m-d');
                $data['practice_start'] = Carbon::parse($practice->practice_start)->format('Y-m-d');
                $data['practice_end'] = Carbon::parse($practice->practice_end)->format('Y-m-d');
            }
        }

        $data['selectedDirectionId'] = $selectedDirectionId;
        $data['selectedCourseId'] = $selectedCourseId;
        $data['directions'] = $this->direction->get();
        $data['courses'] = $this->course->whereBetween('course', [1, 4])->get();

        return view('admin.practice-info', $data);
    }

    /**
     * Изменение информации и сроков по направлению-курсу
     * @param Request $request
     * @param $directionId
     * @param $courseId
     * @return \Illuminate\Http\RedirectResponse
     */
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
