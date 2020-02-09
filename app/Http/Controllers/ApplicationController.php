<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationRequest;
use App\Teacher;
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
        $studentId = $user->student()->value('id');
        $applicationTypeId = $request['type_id'];
        $teacherFullName = $teacher->getFullName();

        if (!($this->application->applicationExists($studentId, $applicationTypeId))) {

            $this->application->create([
                'student_id' => $studentId,
                'teacher_id' => $teacher->id,
                'type_id' => $applicationTypeId,
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
            $failureMessage = "Вы уже записаны на практику";
            if ($applicationTypeId == 1) {
                $failureMessage = "У вас уже есть заявка на практику у преподавателя " . $teacherFullName .
                    ". Вы можете отменить её в личном кабинете.";
            } elseif ($applicationTypeId == 2) {
                $failureMessage = "У вас уже есть заявка на диплом у преподавателя " . $teacherFullName .
                    ". Вы можете отменить её в личном кабинете.";
            }

            return "{
                \"status\": false,
                \"message\": \"$failureMessage\"
            }";
        }
    }
}
