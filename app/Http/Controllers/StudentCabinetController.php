<?php

namespace App\Http\Controllers;

use App\Application;
use App\Http\Requests\UpdateStudentPasswordRequest;
use App\Http\Requests\UpdateStudentLoginRequest;
use App\Student;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentCabinetController extends Controller
{
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
        $currentApplications = Application::where('student_id', '=', $student->id)->get();
        return view('student-profile', ['currentApplications' => $currentApplications, 'student' => $student]);
    }

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
