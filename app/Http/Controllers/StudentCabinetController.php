<?php

namespace App\Http\Controllers;

use App\Application;
use App\File;
use App\Helpers\Helper;
use App\Http\Requests\UpdateStudentPasswordRequest;
use App\Http\Requests\UpdateStudentLoginRequest;
use App\Student;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentCabinetController extends Controller
{
    /**
     * StudentCabinetController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        // Проверка пользователя на студента
        $this->middleware('student');
    }

    /**
     * Возвращает профиль студента
     *
     * @param Authenticatable $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Authenticatable $user)
    {
        $student = $user->student()->first();
        // Получение текущей заявки
        $currentApplication = Application::where([
            ['student_id', '=', $student->id],
            ['year', '=', Helper::getSchoolYear()],
            ['type_id', '=', 1]
        ])->get()->last();

        $historyApplications = Application::where([
            ['student_id', '=', $student->id],
            ['type_id', '=', 1]
        ])->whereIn('status_id', [2, 3])->get();

        $files = File::orderByDesc('created_at')->get();

        return view('student-profile', ['currentApplication' => $currentApplication,
                                              'historyApplications' => $historyApplications,
                                              'student' => $student,
                                              'files' => $files]);
    }

    /**
     * Обновление пароля студента при совпадении старого пароля пришедшего от пользователя и пароля в базе
     *
     * @param UpdateStudentPasswordRequest $request
     * @param Student $student
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(UpdateStudentPasswordRequest $request, Student $student)
    {
        $user = $student->user()->first();

        if (Hash::check($request->old_password, $user->password)) {
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            return back()->with('result',  ['status' => 'success', 'message' => 'Пароль успешно изменен']);
        }

        return back()->with('result',  ['status' => 'failure', 'message' => 'Неверный старый пароль']);
    }

    /**
     * Обновление логина студента при верном пароле
     *
     * @param UpdateStudentLoginRequest $request
     * @param Student $student
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateLogin(UpdateStudentLoginRequest $request, Student $student)
    {
        $user = $student->user()->first();
        if (Hash::check($request->password, $user->password)) {
            $user->update([
                'login' => $request->login
            ]);

            return back()->with('result',  ['status' => 'success', 'message' => 'Логин успешно изменен']);
        }

        return back()->with('result',  ['status' => 'failure', 'message' => 'Неверный пароль']);
    }
}
