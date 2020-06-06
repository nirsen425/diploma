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

                $files = $request->file('files');
                if($request->hasFile('files'))
                {
                    $exFile = array();
                    foreach ($files as $file) {
                        $fullName = $file->getClientOriginalName();
                        $name = pathinfo($fullName, PATHINFO_FILENAME);
                        $extension = $file->getClientOriginalExtension();

                        $path = '/public/files/' . $directionCode . ' ' . $directionName . '/' . $courseName . ' курс';

                        $exist = Storage::disk('local')->exists($path . '/' . $name . '.' . $extension);

                        if($exist)
                        {
                            array_push($exFile, $fullName);
                            continue;
                        }
                        else
                        {
                            $putPath = Storage::putFileAs($path, $file, $name . '.' . $extension);

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
                        $exNames = implode('", "', $exFile);
                        return back()->with('notify_failure', 'Файлы: "' . $exNames . '" не были загружены, они уже существуют.');
                    }
                }
            }
        }
    }

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

                        return "{
                            \"status\": true
                        }";
                    }
                } else {
                    return "{
                        \"status\": false
                    }";
                }
            }
        }
    }
}
