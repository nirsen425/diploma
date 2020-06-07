<?php

namespace App\Http\Controllers\Admin;

use App\Course;
use App\Direction;
use App\Http\Controllers\Controller;
use App\File;
use App\Group;
use Illuminate\Http\Request;
use App\Http\Requests\UploadFiles;
use Illuminate\Support\Facades\Storage;
use Validator;

class AdminFilesController extends Controller
{
    protected $file;
    protected $direction;
    protected $course;

    public function __construct(File $file, Direction $direction, Course $course)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->file = $file;
        $this->direction = $direction;
        $this->course = $course;
    }

    /**
     * Вывод файлов по направлению-курсу
     * @param $directionId
     * @param null $courseId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($directionId, $courseId = null)
    {
        if (isset($directionId))
        {
            $direction = $this->direction->where('id', '=', $directionId)->first();
            if (isset($courseId))
            {
                $directionCourseFiles = $direction->files()->orderByDesc('created_at')->wherePivot('course_id', '=', $courseId)->get();
                if (isset($directionCourseFiles))
                {
                    $data['directionCourseFiles'] = $directionCourseFiles;
                }
            }
        }

        $data['selectedDirectionId'] = $directionId;
        $data['selectedCourseId'] = $courseId;
        $data['directions'] = $this->direction->get();
        $data['courses'] = $this->course->whereBetween('course', [1, 4])->get();

        return view('admin.files', $data);
    }

    /**
     * Загрузка файлов с привязкой по направлению-курсу
     * @param Request $request
     * @param $directionId
     * @param $courseId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function upload(Request $request, $directionId, $courseId)
    {
        if (isset($directionId))
        {
            $direction = $this->direction->where('id', '=', $directionId)->first();
            $directionCode = $direction->direction;
            $directionName = $direction->direction_name;

            if (isset($courseId))
            {
                $course = $this->course->where('id', '=', $courseId)->first();
                $courseName = $course->course;

                // Валидация
                $validator = Validator::make($request->all(), [
                    'files' => 'array',
                    'files.*' => 'required|file|mimes:doc, docx, xls, xlsx, pptx,
                                                      rtf, pdf, jpg, jpeg, png'
                ]);

                if (!$validator->fails()) {
                    $files = $request->file('files');
                    if($request->hasFile('files'))
                    {
                        $exFile = array();
                        foreach ($files as $file) {
                            // Имя + расширение
                            $fullName = $file->getClientOriginalName();
                            // Имя
                            $name = pathinfo($fullName, PATHINFO_FILENAME);
                            // Расширение
                            $extension = $file->getClientOriginalExtension();
                            // Путь для сохранения
                            $path = '/public/files/' . $directionCode . ' ' . $directionName . '/' . $courseName . ' курс';

                            // Проверка существования текущего файла
                            $exist = Storage::disk('local')->exists($path . '/' . $name . '.' . $extension);
                            if($exist)
                            {
                                // Если некоторые файлы уже существуют, не загружаем их, записываем их имя в массив для вывода
                                array_push($exFile, $fullName);
                                // Прекращаем итерацию
                                continue;
                            }
                            else
                            {
                                // Сохранение файла
                                $putPath = Storage::putFileAs($path, $file, $name . '.' . $extension);
                                // Добавление записи в БД
                                if ($putPath)
                                {
                                    $direction->files()->save(
                                        $this->file->create([
                                            'name' => $name,
                                            'extension' => $extension,
                                            'path' => $path
                                        ]),
                                        ['course_id' => $courseId]
                                    );
                                }
                            }
                        }
                        if(!$exFile)
                        {
                            return back()->with('notify_success', 'Все файлы успешно загружены.');
                        }
                        else
                        {
                            // Объединяем массив имен существующих файлов в строку через ',' и выводим сообщение
                            $exNames = implode('", "', $exFile);
                            return back()->with('notify_failure', 'Файлы: "' . $exNames . '" не были загружены, они уже существуют.');
                        }
                    }
                }
                else
                {
                    // Сообщение несоответствия расширений файлов
                    return back()->with('notify_failure', 'Типы файлов не соответствуют требованиям: .doc, .docx, .xls, .xlsx, .pptx, .rtf, .pdf, .jpg, .jpeg, .png');
                }
            }
        }
    }

    /**
     * Скачивание файла
     * @param $directionId
     * @param $courseId
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function download($directionId, $courseId, $id)
    {
        if (isset($directionId)) {
            $direction = $this->direction->where('id', '=', $directionId)->first();
            $directionCode = $direction->direction;
            $directionName = $direction->direction_name;

            if (isset($courseId)) {
                $course = $this->course->where('id', '=', $courseId)->first();
                $courseName = $course->course;

                $path = '/public/files/' . $directionCode . ' ' . $directionName . '/' . $courseName . ' курс';

                // Поиск по id
                $file = File::find($id);
                $search = Storage::disk('local')->exists($path . '/' . $file->name . '.' . $file->extension);

                if ($search) {
                    return Storage::disk('local')->download($path . '/' . $file->name . '.' . $file->extension);

                } else {
                    return back()->with('notify_failure', 'Не удалось скачать файл ' . '"' . $file->name . '.' . $file->extension . '".');
                }
            }
        }
    }

    /**
     * Удаление файла
     * @param $directionId
     * @param $courseId
     * @param $id
     * @return string
     */
    public function destroy($directionId, $courseId, $id)
    {
        if (isset($directionId))
        {
            $direction = $this->direction->where('id', '=', $directionId)->first();
            $directionCode = $direction->direction;
            $directionName = $direction->direction_name;

            if (isset($courseId))
            {
                $course = $this->course->where('id', '=', $courseId)->first();
                $courseName = $course->course;

                $path = '/public/files/' . $directionCode . ' ' . $directionName . '/' . $courseName . ' курс';

                $file = File::find($id);
                $search = Storage::disk('local')->exists($path . '/' . $file->name . '.' . $file->extension);

                if ($search) {
                    $del = Storage::disk('local')->delete($path . '/' . $file->name . '.' . $file->extension);

                    if ($del) {
                        $file->delete();

                        // Если удален, возвращаем в ajax 'true'
                        return "{
                            \"status\": true
                        }";
                    }
                } else {
                    // Если нет - 'false'
                    return "{
                        \"status\": false
                    }";
                }
            }
        }
    }
}
