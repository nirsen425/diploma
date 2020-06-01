<?php

namespace App\Http\Controllers;

use App\Application;
use App\File;
use App\Helpers\Helper;
use App\Http\Requests\UpdateTeacherFullDescriptionRequest;
use App\Http\Requests\UpdateTeacherPhotoRequest;
use App\Http\Requests\UpdateTeacherShortDescriptionRequest;
use App\ImageService;
use App\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateTeacherPasswordRequest;
use App\Http\Requests\UpdateTeacherLoginRequest;

class TeacherCabinetController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->middleware('auth');
        // Проверка пользователя на преподавателя
        $this->middleware('teacher');
        $this->imageService = $imageService;
    }

    /**
     * Возвращает профиль преподавателя
     *
     * @param Authenticatable $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Authenticatable $user)
    {
        $teacher = $user->teacher()->first();
        // Получение нерассмотренных учителем заявок за текущий год
        $teacherWaitApplications = $teacher->applications()->where([
            ['status_id', '=', 1],
            ['year', '=', Helper::getSchoolYear()]
        ])->get();

        // Получение подтвержденных учителем заявок на практику за текущий год
        $confirmPracticeApplications = $teacher->applications()->where([
            ['status_id', '=', 2],
            ['type_id', '=', 1],
            ['year', '=', Helper::getSchoolYear()]
        ])->get();

        // Получение студентов которым преподаватель одобрил заявку на практику за текущий год
        foreach ($confirmPracticeApplications as $confirmPracticeApplication) {
            $confirmPracticeApplicationStudents[] = $confirmPracticeApplication->student()->first();
        }

        // Получение подтвержденных учителем заявок на диплом за текущий год
//        $confirmDiplomaApplications = $teacher->applications()->where([
//            ['status_id', '=', 2],
//            ['type_id', '=', 2],
//            ['year', '=', Helper::getSchoolYear()]
//        ])->get();

        // Получение студентов которым преподаватель одобрил заявку на диплом за текущий год
//        foreach ($confirmDiplomaApplications as $confirmDiplomaApplication) {
//            $confirmDiplomaApplicationStudents[] = $confirmDiplomaApplication->student()->first();
//        }



        $files = File::orderByDesc('created_at')->get();



        $data = [
            'teacher' => $teacher,
            'currentYear' => Helper::getSchoolYear(),
            'files' => $files
        ];

        //<< Помещение заявок в массив для шаблона, если они есть
        if (!empty($teacherWaitApplications)) {
            $data['teacherWaitApplications'] = $teacherWaitApplications;
        }

        if (!empty($confirmPracticeApplicationStudents)) {
            $data['confirmPracticeApplicationStudents'] = $confirmPracticeApplicationStudents;
        }

//        if (!empty($confirmDiplomaApplicationStudents)) {
//            $data['confirmDiplomaApplicationStudents'] = $confirmDiplomaApplicationStudents;
//        }
        //>>
        return view('teacher-profile', $data);
    }

    /**
     * Обновление пароля преподавателя при совпадении старого пароля пришедшего от пользователя и пароля в базе
     *
     * @param UpdateTeacherPasswordRequest $request
     * @param Teacher $teacher
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(UpdateTeacherPasswordRequest $request, Teacher $teacher)
    {
        $user = $teacher->user()->first();

        if (Hash::check($request->old_password, $user->password)) {
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            return back()->with('result',  ['status' => 'success', 'message' => 'Пароль успешно изменен']);
        }

        return back()->with('result',  ['status' => 'failure', 'message' => 'Неверный старый пароль']);
    }

    /**
     * Обновление логина преподавателя при верном пароле
     *
     * @param UpdateTeacherLoginRequest $request
     * @param Teacher $teacher
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateLogin(UpdateTeacherLoginRequest $request, Teacher $teacher)
    {
        $user = $teacher->user()->first();
        if (Hash::check($request->password, $user->password)) {
            $user->update([
                'login' => $request->login
            ]);

            return back()->with('result',  ['status' => 'success', 'message' => 'Логин успешно изменен']);
        }

        return back()->with('result',  ['status' => 'failure', 'message' => 'Неверный пароль']);
    }

    public function updatePhoto(UpdateTeacherPhotoRequest $request, Teacher $teacher)
    {
        $image = $request['photo'];

        // Получаем координаты точки и размеры для обрезки изображения
        $cropCoordX = (integer)$request['photo_x'];
        $cropCoordY = (integer)$request['photo_y'];
        $cropWidth = (integer)$request['photo_width'];
        $cropHeight = (integer)$request['photo_height'];
        // Обрезаем сохраняем изображение и получаем его имя
        $cropPhotoName = $this->imageService
            ->handleUploadedImage($image, $cropCoordX, $cropCoordY, $cropWidth, $cropHeight);

        $teacher->update([
            'photo' => $cropPhotoName
        ]);

        return back()->with('result',  ['status' => 'success', 'message' => 'Фото успешно изменено']);
    }

    public function updateShortDescription(UpdateTeacherShortDescriptionRequest $request, Teacher $teacher)
    {
        $teacher->update([
            'short_description' => $request->short_description
        ]);

        return back()->with('result',  ['status' => 'success', 'message' => 'Краткое описание успешно изменено']);
    }

    public function updateFullDescription(UpdateTeacherFullDescriptionRequest $request, Teacher $teacher)
    {
        $teacher->update([
            'full_description' => $request->full_description
        ]);

        return back()->with('result',  ['status' => 'success', 'message' => 'Полное описание успешно изменено']);
    }
}
