<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\File;
use App\Group;
use Illuminate\Http\Request;
//use App\Http\Requests\UploadFiles;
use Illuminate\Support\Facades\Storage;

class AdminFilesController extends Controller
{
    protected $file;

    public function __construct(File $file)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->file = $file;
    }

    public function index()
    {
        $files = File::orderByDesc('created_at')->get();
        return view('admin.files', ['files' => $files]);
    }

    public function upload(Request $request)
    {
        $files = $request->file('files');
        if($request->hasFile('files'))
        {
            $exFile = array();
            foreach ($files as $file) {
                $fullName = $file->getClientOriginalName();
                $name = pathinfo($fullName, PATHINFO_FILENAME);
                $ext = $file->getClientOriginalExtension();
                $exist = Storage::disk('local')->exists('/public/files/'. $name . '.' . $ext);
                if($exist)
                {
                    array_push($exFile, $fullName);
                    continue;
                }
                else
                {
                    $path = Storage::putFileAs('public/files', $file, $name . '.' . $ext);
                    if ($path)
                    {
                        $this->file->create([
                            'name' => $name,
                            'extension' => $ext
                        ]);
                    }
                }
            }
            if(!$exFile)
            {
                return back()->with('notify_success', 'Все файлы успешно загружены.');
            }
            else
            {
                $exNames = implode(', ', $exFile);
                return back()->with('notify_failure', 'Файлы: ' . $exNames . ' не были загружены, они уже существуют.');
            }
        }
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
            return back()->with('notify_failure', 'Не удалось скачать файл ' . '"' . $file->name . '.' . $file->extension . '".');
        }
    }

    public function destroy($id)
    {
        $file = File::find($id);
        $search = Storage::disk('local')->exists('/public/files/'. $file->name . '.' . $file->extension);
        if ($search)
        {
            $del = Storage::disk('local')->delete('/public/files/'. $file->name . '.' . $file->extension);
            if ($del)
            {
                $file->delete();
                return "{
                    \"status\": true
                }";
            }
        }
        else
        {
            return "{
                \"status\": false
            }";
        }
    }
}
