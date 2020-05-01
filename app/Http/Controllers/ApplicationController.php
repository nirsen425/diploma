<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\ApplicationRequest;
use App\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Application;
use Illuminate\Contracts\Auth\Authenticatable;

class ApplicationController extends Controller
{
    protected $application;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * Отправляет заявку преподавателю от пользователя, если у него нет больше заявок
     *
     * @param ApplicationRequest $request
     * @param Authenticatable $user
     * @param Teacher $teacher
     * @return string
     */
    public function store(ApplicationRequest $request, Authenticatable $user, Teacher $teacher)
    {
        $student = $user->student()->first();
        $studentId = $student->id;
        $applicationTypeId = $request['type_id'];
        $teacherFullName = $teacher->getFullName();
        $existApplication = $this->application->waitOrConfirmApplicationExistByCurrentYear($studentId, $applicationTypeId);

        if (!($existApplication)) {

            $this->application->create([
                'student_id' => $studentId,
                'teacher_id' => $teacher->id,
                'type_id' => $applicationTypeId,
                'year' => Helper::getSchoolYear(),
                'status_id' => 1
            ]);

            $succesMessage = "Заявка отправлена";
            if ($applicationTypeId == 1) {
                $succesMessage = "Заявка на практику успешно отправлена к преподавателю " . $teacherFullName . ".";
            } elseif ($applicationTypeId == 2) {
                $succesMessage = "Заявка на диплом успешно отправлена к преподавателю " . $teacherFullName . ".";
            }

            return "{
                \"status\": true,
                \"message\": \"$succesMessage\"
            }";
        } else {
            $teacherFullName = $student->getTeacherByTypeActivity($applicationTypeId)->getFullName();

            $failureMessage = "Вы уже записаны на практику";
            if ($applicationTypeId == 1) {
                $failureMessage = "У вас уже есть заявка на практику у преподавателя $teacherFullName.";
                if ($existApplication->status_id == 1) {
                    $failureMessage .= " Вы можете отменить её в личном кабинете.";
                }
            } elseif ($applicationTypeId == 2) {
                $failureMessage = "У вас уже есть заявка на диплом у преподавателя $teacherFullName.";
                if ($existApplication->status_id == 1) {
                    $failureMessage .= "Вы можете отменить её в личном кабинете.";
                }
            }

            return "{
                \"status\": false,
                \"message\": \"$failureMessage\"
            }";
        }
    }

    public function confirm(Authenticatable $user, $studentId, $typeId)
    {
        $teacherId = $user->teacher()->value('id');

        $application = $this->application->where([
            ['teacher_id', '=', $teacherId],
            ['student_id', '=', $studentId],
            ['type_id', '=', $typeId],
            ['year', '=', Helper::getSchoolYear()],
            ['status_id', '=', 1]
        ])->first();

        if (!empty($application)) {
            $application->status_id = 2;
            $application->reply_datetime = date("Y-m-d H:i:s");
            $application->save();

            return "true";
        }

        return "false";
    }

    public function reject(Authenticatable $user, $studentId, $typeId)
    {
        $teacherId = $user->teacher()->value('id');

        $application = $this->application->where([
            ['teacher_id', '=', $teacherId],
            ['student_id', '=', $studentId],
            ['type_id', '=', $typeId],
            ['year', '=', Helper::getSchoolYear()]
        ])->whereIn('status_id', [1, 2])->first();

        if (!empty($application)) {
            $application->status_id = 3;
            $application->reply_datetime = date("Y-m-d H:i:s");
            $application->save();

            return "true";
        }

        return "false";
    }

    public function cancel(Authenticatable $user, $teacherId, $typeId)
    {
        $studentId = $user->student()->value('id');

        $application = $this->application->where([
            ['teacher_id', '=', $teacherId],
            ['student_id', '=', $studentId],
            ['type_id', '=', $typeId],
            ['year', '=', Helper::getSchoolYear()],
            ['status_id', '=', 1]
        ])->first();

        if (!empty($application)) {
            $application->delete();

            return "true";
        }

        return "false";
    }

    public function getFreePracticePlaces(Authenticatable $user)
    {
        return $user->teacher()->first()->countFreePracticePlaces();
    }
}
