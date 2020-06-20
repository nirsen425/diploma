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
        $this->middleware('auth');
        $this->application = $application;
    }

    /**
     * Отправляет заявку преподавателю от студента, кроме случая когда у студента уже есть
     * заявка на рассмотрении или уже принятая
     *
     * @param ApplicationRequest $request
     * @param Authenticatable $user
     * @param Teacher $teacher
     * @return string
     */
    public function store(ApplicationRequest $request, Authenticatable $user, Teacher $teacher)
    {
        // Получение студента, который отправил заявку
        $student = $user->student()->first();

        if (!$student->hasAccessForSendApplicationForTeacher($teacher)) {
            $permissionErrorMessage = "Вы не можете отправить заявку этому преподавателю";
            return "{
                \"status\": false,
                \"message\": \"$permissionErrorMessage\"
            }";
        }

        $studentId = $student->id;
        // Получение типа заявки, практика(1) или диплом(2)
        $applicationTypeId = 1;
        $teacherFullName = $teacher->getFullName();
        // Получение заявки в рассмотрении или принятой на текущий учебный год в зависимости от типа заявки
        $waitOrConfirmApplicationExistByCurrentYear = $this->application->waitOrConfirmApplicationExistByCurrentYear($studentId, $applicationTypeId);

        if (!($waitOrConfirmApplicationExistByCurrentYear)) {

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
            $teacherFullName = $waitOrConfirmApplicationExistByCurrentYear->teacher()->first()->getFullName();

            $failureMessage = "Вы уже записаны на практику";
            if ($applicationTypeId == 1) {
                $failureMessage = "У вас уже есть заявка на практику у преподавателя $teacherFullName.";
                if ($waitOrConfirmApplicationExistByCurrentYear->status_id == 1) {
                    $failureMessage .= " Вы можете отменить её в личном кабинете.";
                }
            } elseif ($applicationTypeId == 2) {
                $failureMessage = "У вас уже есть заявка на диплом у преподавателя $teacherFullName.";
                if ($waitOrConfirmApplicationExistByCurrentYear->status_id == 1) {
                    $failureMessage .= "Вы можете отменить её в личном кабинете.";
                }
            }

            return "{
                \"status\": false,
                \"message\": \"$failureMessage\"
            }";
        }
    }

    /**
     * Подтверждение заявки преподавателем
     *
     * @param Authenticatable $user
     * @param $studentId
     * @param $typeId
     * @return string
     */
    public function confirm(Authenticatable $user, $studentId, $typeId)
    {
        // Получение преподавателя, который подтвердил заявку
        $teacherId = $user->teacher()->value('id');

        $application = $this->application->where([
            ['teacher_id', '=', $teacherId],
            ['student_id', '=', $studentId],
            ['type_id', '=', 1],
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

    /**
     * Отклонение заявки преподавателем
     *
     * @param Authenticatable $user
     * @param $studentId
     * @param $typeId
     * @return string
     */
    public function reject(Authenticatable $user, $studentId, $typeId)
    {
        // Получение преподавателя, который отклонил заявку в рассмотрении или уже подтвержденную
        $teacherId = $user->teacher()->value('id');

        $application = $this->application->where([
            ['teacher_id', '=', $teacherId],
            ['student_id', '=', $studentId],
            ['type_id', '=', 1],
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

    /**
     * Отмена(удаление) заявки в рассмотрении отправленной студентом
     *
     * @param Authenticatable $user
     * @param $teacherId
     * @param $typeId
     * @return string
     */
    public function cancel(Authenticatable $user, $teacherId, $typeId)
    {
        // Получение студента, который отправил заявку
        $studentId = $user->student()->value('id');

        $application = $this->application->where([
            ['teacher_id', '=', $teacherId],
            ['student_id', '=', $studentId],
            ['type_id', '=', 1],
            ['year', '=', Helper::getSchoolYear()],
            ['status_id', '=', 1]
        ])->first();

        if (!empty($application)) {
            $application->delete();

            return "true";
        }

        return "false";
    }

    /**
     * Получение количеста свободных мест для преподавателя
     *
     * @param Authenticatable $user
     * @return mixed
     */
    public function getFreePracticePlaces(Authenticatable $user)
    {
        return $user->teacher()->first()->countFreePracticePlaces();
    }
}
