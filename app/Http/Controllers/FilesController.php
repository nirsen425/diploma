<?php

namespace App\Http\Controllers;

use App\Course;
use App\Direction;
use App\File;
use App\Group;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FilesController extends Controller
{
    protected $file;
    protected $direction;
    protected $course;

    public function __construct(File $file, Direction $direction, Course $course)
    {
        $this->middleware('auth');
        $this->file = $file;
        $this->direction = $direction;
        $this->course = $course;
    }

    public function studentDownload(Authenticatable $user, $id)
    {
        $student = $user->student()->first();
        $group = $student->group()->first();
        $direction = $group->direction()->first();
        $course = $group->course()->first();
        $directionCode = $direction->direction;
        $directionName = $direction->direction_name;
        $courseName = $course->course;

        $path = '/public/files/' . $directionCode . ' ' . $directionName . '/' . $courseName . ' курс';

        $file = File::find($id);
        $search = Storage::disk('local')->exists($path . '/'. $file->name . '.' . $file->extension);
        if ($search)
        {
            return Storage::disk('local')->download($path . '/'. $file->name . '.' . $file->extension);
        }
        else
        {
            return back()->with('notify_failure', 'Не удалось скачать файл ' . '"' . $file->name . '.' . $file->extension . '"');
        }
    }

    public function teacherDownload($directionId, $courseId, $id)
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
}
