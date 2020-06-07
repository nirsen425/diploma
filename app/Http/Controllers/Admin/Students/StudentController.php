<?php

namespace App\Http\Controllers\Admin\Students;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStudent;
use App\Student;
use App\StudentGroupStory;
use App\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    protected $student;
    protected $studentGroupStory;

    /**
     * StudentController constructor.
     */
    public function __construct(Student $student, StudentGroupStory $studentGroupStory)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->student = $student;
        $this->studentGroupStory = $studentGroupStory;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $students = $this->student->orderBy('surname')->get();
        return view('admin.students.index', ['students' => $students]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        return view('admin.students.show', ['student' => $student]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        return view('admin.students.edit', ['student' => $student]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function update(UpdateStudent $request, Student $student)
    {
        try {
            DB::beginTransaction();

            $userData = [
                'rights_id' => $request['rights'],
                'login' => $request['login']
            ];

            $password = $request->password;
            if (!empty($password)) {
                $userData['password'] = Hash::make($password);
            }

            $student->user()->update($userData);

            $student->update([
                'name' => $request['name'],
                'patronymic' => $request['patronymic'],
                'surname' => $request['surname']
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return back()->with('status', 'Студент успешно изменен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Student $student)
    {
        try {
            DB::beginTransaction();

            // Удаление студента и связанного с ним пользователя
            $user = $student->user()->first();
            $this->studentGroupStory->where([
                'student_id' => $student->id,
            ])->delete();
            $student->delete();
            $user->delete();



            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return "false";
        }

        return "true";
    }
}
