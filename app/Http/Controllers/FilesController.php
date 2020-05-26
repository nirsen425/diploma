<?php

namespace App\Http\Controllers;

use App\File;
use App\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FilesController extends Controller
{
    protected $file;

    public function __construct(File $file)
    {
        $this->middleware('auth');
        $this->file = $file;
    }

    public function download($id)
    {
        $file = File::find($id);
        $search = Storage::disk('local')->exists('/public/files/'. $file->name . '.' . $file->extension);
        if ($search)
        {
            return Storage::disk('local')->download('public/files/'. $file->name . '.' . $file->extension);
        }
        else
        {
            return back()->with('notify_failure', 'Не удалось скачать файл ' . '"' . $file->name . '.' . $file->extension . '"');
        }
    }
}
