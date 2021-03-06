<?php

namespace App\Http\Controllers\Admin;

use App\Application;
use App\ApplicationStatus;
use App\GroupStory;
use App\Student;
use App\StudentGroupStory;
use App\Teacher;
use App\TeacherLimit;
use App\Group;
use App\Helpers\Helper;
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
    protected $studentGroupStory;

    /**
     * AdminController constructor.
     * @param Application $application
     * @param Teacher $teacher
     * @param Student $student
     */
    public function __construct(Application $application, Teacher $teacher, Student $student,
                                TeacherLimit $teacherLimit, GroupStory $groupStory,
                                ApplicationStatus $applicationStatus, Group $group,
                                StudentGroupStory $studentGroupStory)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->application = $application;
        $this->teacher = $teacher;
        $this->student = $student;
        $this->teacherLimit = $teacherLimit;
        $this->groupStory = $groupStory;
        $this->applicationStatus = $applicationStatus;
        $this->group = $group;
        $this->studentGroupStory = $studentGroupStory;
    }

    /**
     * Показывает сколько студентов преподаватели берут на практику и какие курсы, сортируя по году
     *
     * @param $year Выбранный год из списка
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showTeacherLimitsPage($year)
    {
        $teachers = $this->teacher->all()->sortBy('surname');
        // Года для выпадающего списка
        $limitYears = $this->teacherLimit->select('year')->distinct()->orderBy('year')->get();
        // Проверка существования года в выпадающем списке для возможного добавление нового года в выпадющий список
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

    /**
     * Выводим для каждого студента его заявку сортируя по группе и году
     *
     * @param $historyYear Год из таблицы group_stories, выбранный в выпадающем списке
     * @param null $groupStoryId Id группы из group_stories, выбранной в выпадающем списке
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showStudentLastApplications($historyYear = null, $selectedGroupId = null)
    {
        // Получаем уникальные года из истории групп для вывода в выпадающий список
        $yearsGroup = $this->groupStory->select('year_history')->distinct()
            ->orderBy('year_history')->get()->pluck('year_history')->toArray();

        if (!in_array($historyYear, $yearsGroup)) {
            return view('admin.student-applications');
        }

        if (isset($selectedGroupId)) {
            $selectedGroup = $this->groupStory->where([
                ['id', '=', $selectedGroupId],
                ['year_history', '=', $historyYear]
            ])->first();

            if (!isset($selectedGroup)) {
                return view('admin.student-applications');
            }

            $students = $selectedGroup->students()->orderBy('surname')->get();
            $teachers = $this->teacher->getTeachersByCourseForYear($selectedGroup->course_id, $historyYear);
            $parameters['students'] = $students;
            $parameters['teachers'] = $teachers;
            $parameters['selectedGroup'] = $selectedGroup;
        }

        $groupsBySelectedYear = $this->groupStory->where('year_history', '=', $historyYear)->orderBy('name')->get();

        $parameters['groupsBySelectedYear'] = $groupsBySelectedYear;
        $parameters['historyYear'] = $historyYear;
        $parameters['yearsGroup'] = $yearsGroup;
        $parameters['applicationStatuses'] = $this->applicationStatus->all();


        return view('admin.student-applications', $parameters);
    }

    /**
     * Изменяет последнюю существующую заявку(студента, преподавателя, статус, время ответа при опредленном условии)
     * из данных запроса или создает новую, если заявок нет
     *
     * @param Request $request
     * @return string
     */
    public function changeOrCreateApplication(Request $request)
    {
        // Получение массива с данными о заявке из ajax запроса
        $applictionDataArray = $request->applictionDataArray;

        if (!in_array($applictionDataArray['statusId'], [1,2,3])) {
            return "false";
        }

        $studentGroup = $this->groupStory->where([
            ['id', '=', $applictionDataArray['groupId']],
            ['year_history', '=', $applictionDataArray['year']]
        ])->first();

        $teachers = $this->teacher
            ->getTeachersByCourseForYear($studentGroup->course_id, $applictionDataArray['year']);

        foreach ($teachers as $teacher) {
            $teachersId[] = $teacher->id;
        }

        if (!in_array($applictionDataArray['teacherId'], $teachersId)) {
            return "false";
        }

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

            // Если статус существующей заявки - ожидание, значит даты ответа нет
            if ($applictionDataArray['statusId'] == 1) {
                $parameters['reply_datetime'] = null;
            }

            $lastApplication->update($parameters);
        } else {
            $yearsGroup = $this->groupStory->select('year_history')->distinct()
                ->orderBy('year_history')->get()->pluck('year_history')->toArray();

            // Проверка на существующий год
            $historyYear = $applictionDataArray['year'];
            if (!in_array($historyYear, $yearsGroup)) {
                return "false";
            }

            $selectedGroup = $this->groupStory->where([
                ['id', '=', $applictionDataArray['groupId']],
                ['year_history', '=', $historyYear]
            ])->first();


            if (!isset($selectedGroup)) {
                return "false";
            }

            $parameters = [
                'year' => $applictionDataArray['year'],
                'student_id' => $applictionDataArray['studentId'],
                'teacher_id' => $applictionDataArray['teacherId'],
                'type_id' => 1,
                'status_id' => $applictionDataArray['statusId'],
                'reply_datetime' => date("Y-m-d H:i:s")
            ];

            // Если статус существующей заявки - ожидание, значит даты ответа нет
            if ($applictionDataArray['statusId'] == 1) {
                $parameters['reply_datetime'] = null;
            }

            $this->application->create($parameters);
        }

        return "true" ;
    }

    /**
     * Вывод списка студентов и их групп руководителя по году и заявке
     * @param $selectedYear
     * @param null $teacherId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showTeacherApplications($selectedYear = null, $teacherId = null)
    {
        $data['selectedYear'] = $selectedYear;

        // Получением уникальные года из заявок для выпадающего списка
        $yearsApplications = $this->application->select('year')->distinct()->orderBy('year')->get();
        $data['yearsApplications'] = $yearsApplications;

        // Получение уникальных teacher_id из заявок, где year = выбраному году из списка
        $applicationsBySelectedYear = $this->application->select('teacher_id')->distinct()->where('year', '=', $selectedYear)->get();

        // Получение списка уникальных руководителей для выпадающего списка
        foreach ($applicationsBySelectedYear as $applicationBySelectedYear)
        {
            $teachersBySelectedYear[] = $applicationBySelectedYear->teacher()->first();

        }
        // Сортировка по фамилии
        if (isset($teachersBySelectedYear))
        {
            $teachersBySelectedYear = collect($teachersBySelectedYear);
            $teachersBySelectedYear = $teachersBySelectedYear->sortBy('surname');
            $teachersBySelectedYear = $teachersBySelectedYear->values()->all();
            $data['teachersBySelectedYear'] = $teachersBySelectedYear;
        }

        // Получение модели 'teacher', если она выбрана в выпадающем списке
        if(isset($teacherId))
        {
            $selectedTeacher = $this->teacher->where('id', '=', $teacherId)->first();
            $data['selectedTeacher'] = $selectedTeacher;
            // Получение одобренных преподавателем заявок на практику
            $practiceApplications = $selectedTeacher->applications()->where([['year', '=', $selectedYear], ['status_id', '=', 2], ['type_id', '=', 1]])->get();
            $data['practiceApplications'] = $practiceApplications;
            // Получение студентов, привязанных к заявкам
            foreach ($practiceApplications as $practiceApplication)
            {
                $students[] = $practiceApplication->student()->first();
            }
            // Сортировка по фамилии
            if (isset($students))
            {
                $students = collect($students);
                $students = $students->sortBy('surname');
                $students = $students->values()->all();
                $data['students'] = $students;
            }
        }

        return view('admin.teacher-applications', $data);
    }
}
