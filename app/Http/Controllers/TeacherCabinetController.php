<?php

namespace App\Http\Controllers;

use App\Application;
use App\Course;
use App\Direction;
use App\File;
use App\Helpers\Helper;
use App\Http\Requests\UpdateTeacherFullDescriptionRequest;
use App\Http\Requests\UpdateTeacherPhotoRequest;
use App\Http\Requests\UpdateTeacherShortDescriptionRequest;
use App\ImageService;
use App\Practice;
use App\Teacher;
use Carbon\Carbon;
use http\Url;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateTeacherPasswordRequest;
use App\Http\Requests\UpdateTeacherLoginRequest;

class TeacherCabinetController extends Controller
{
    protected $imageService;
    protected $direction;
    protected $course;
    protected $practice;
    protected $file;

    public function __construct(ImageService $imageService, Direction $direction, Course $course, Practice $practice, File $file)
    {
        $this->middleware('auth');
        // Проверка пользователя на преподавателя
        $this->middleware('teacher');
        $this->imageService = $imageService;
        $this->direction = $direction;
        $this->course = $course;
        $this->practice = $practice;
        $this->file = $file;
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

        // Получение информации и файлов по умолчанию
        $defaultDirection = $this->direction->where('id', '=', 1)->first();
        $defaultCourse = $this->course->where('id', '=', 1)->first()->id;
        $defaultPractice = $this->practice->where([
            ['direction_id', '=', $defaultDirection->id],
            ['course_id', '=', $defaultCourse],
        ])->first();
        $defaultFiles = $defaultDirection->files()->orderByDesc('created_at')->wherePivot('course_id', '=', $defaultCourse)->get();

        $data = [
            'teacher' => $teacher,
            'currentYear' => Helper::getSchoolYear(),
            'selectedDirectionIdPractice' => $defaultDirection->id,
            'selectedCourseIdPractice' => $defaultCourse,
            'selectedDirectionIdFiles' => $defaultDirection->id,
            'selectedCourseIdFiles' => $defaultCourse,
            'practice' => $defaultPractice,
            'files' => $defaultFiles,
            'directions' => $this->direction->get(),
            'courses' => $this->course->whereBetween('course', [1, 4])->get(),
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
     * Вывод информации при изменении направления-курса
     *
     * @param Request $request
     * @return mixed
     */
    public function getPracticeByDirectionCourse(Request $request)
    {
        $practiceData = $request->practiceData;
        $selectedDirectionId = $practiceData['directionId'];
        $selectedCourseId = $practiceData['courseId'];

        if (isset($selectedDirectionId))
        {
            if (isset($selectedCourseId))
            {
                $practice = $this->practice->where([
                    ['direction_id', '=', $selectedDirectionId],
                    ['course_id', '=', $selectedCourseId]
                ])->first();
                $dataPractice['application_start'] = $practice->application_start;
                $dataPractice['application_end'] = $practice->application_end;
                $dataPractice['practice_start'] = $practice->practice_start;
                $dataPractice['practice_end'] = $practice->practice_end;
                $dataPractice['practice_info'] = $practice->practice_info;
                return $dataPractice;
            }
            else
            {
                return "false";
            }
        }
    }

    /**
     * Вывод файлов при изменении направления-курса
     *
     * @param Request $request
     * @return mixed
     */
    public function getFilesByDirectionCourse(Request $request)
    {
        $filesData = $request->filesData;
        $selectedDirectionId = $filesData['directionId'];
        $selectedCourseId = $filesData['courseId'];

        if (isset($selectedDirectionId))
        {
            $direction = $this->direction->where('id', '=', $selectedDirectionId)->first();
            if (isset($selectedCourseId))
            {
                $directionCourseFiles = $direction->files()->orderByDesc('created_at')->wherePivot('course_id', '=', $selectedCourseId)->get();
                if (!$directionCourseFiles->isEmpty())
                {
                    foreach ($directionCourseFiles as $directionCourseFile)
                    {
                        $dataFiles[] = [
                            'fileId' => $directionCourseFile->id,
                            'fileFullName' => $directionCourseFile->name . '.' . $directionCourseFile->extension,
                            'created_at' => $directionCourseFile->created_at,
                            'urlFile' => route(
                                'teacher_file_download',
                                [
                                    'directionId' => $selectedDirectionId, 'courseId' => $selectedCourseId, 'fileId' => $directionCourseFile->id
                                ])
                        ];
                    }
                    $dataFiles['filesCount'] = count($dataFiles);
                    return $dataFiles;
                }
                else
                {
                    return "false";
                }
            }
        }
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

    public function deletePhoto(Authenticatable $user)
    {
        $teacher = $user->teacher()->first();

        $teacher->update([
            'photo' => 'empty.png'
        ]);

        return "true";
    }
}
