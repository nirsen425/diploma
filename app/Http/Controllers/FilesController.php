<?php

namespace App\Http\Controllers;

use App\File;
use App\Group;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FilesController extends Controller
{
    protected $file;

    public function __construct(File $file)
    {
        $this->middleware('auth');
        $this->middleware('student');
        $this->file = $file;
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

    public function teacherDownload()
    {

    }
}
