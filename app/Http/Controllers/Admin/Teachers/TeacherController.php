<?php

namespace App\Http\Controllers\Admin\Teachers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTeacher;
use App\ImageService;
use App\Teacher;
use DB;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->imageService = $imageService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.teachers.index', ['teachers' => Teacher::all()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function show(Teacher $teacher)
    {
        return view('admin.teachers.show', ['teacher' => $teacher]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function edit(Teacher $teacher)
    {
        return view('admin.teachers.edit', ['teacher' => $teacher]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Teacher  $teacher
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function update(UpdateTeacher $request, Teacher $teacher)
    {
        try {
            DB::beginTransaction();

            $userData = [
                'user_type_id' => 2,
                'rights_id' => $request['rights'],
                'login' => $request['login'],
                'email' => $request['email']
            ];

            $password = $request->password;
            if (!empty($password)) {
                $userData['password'] = Hash::make($password);
            }

            $teacher->user()->update($userData);

            $image = $request['photo'];

            $teacherData = [
                'name' => $request['name'],
                'patronymic' => $request['patronymic'],
                'surname' => $request['surname'],
                'short_description' => $request['short_description'],
                'full_description' => $request['full_description']
            ];

            if ($image) {
                // Получаем координаты точки и размеры для обрезки изображения
                $cropCoordX = (integer)$request['photo_x'];
                $cropCoordY = (integer)$request['photo_y'];
                $cropWidth = (integer)$request['photo_width'];
                $cropHeight = (integer)$request['photo_height'];
                // Обрезаем и сохраняем изображение, получаем его имя
                $cropPhotoName = $this->imageService
                    ->handleUploadedImage($image, $cropCoordX, $cropCoordY, $cropWidth, $cropHeight);

                $teacherData['photo'] = $cropPhotoName;
            }

            $teacher->update($teacherData);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return back()->with('status', 'Преподаватель успешно изменен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Teacher  $teacher
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Teacher $teacher)
    {
        try {
            DB::beginTransaction();

            // Удаление преподавателя и связанного с ним пользователя
            $user = $teacher->user()->first();
            $teacher->delete();
            $user->delete();


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return "false";
        }

        return "true";
    }
}
