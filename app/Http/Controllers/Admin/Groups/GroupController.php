<?php

namespace App\Http\Controllers\Admin\Groups;

use App\Course;
use App\Direction;
use App\Group;
use App\GroupStory;
use App\Http\Requests\UpdateGroupRequest;
use App\Student;
use App\User;
use DB;
use Illuminate\Support\Str;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateGroupRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GroupController extends Controller
{
    protected $course;
    protected $direction;
    protected $group;
    protected $groupStory;
    protected $user;
    protected $student;

    /**
     * GroupController constructor.
     */
    public function __construct(Course $course, Direction $direction,
                                Group $group, GroupStory $groupStory,
                                User $user, Student $student)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->course = $course;
        $this->direction = $direction;
        $this->group = $group;
        $this->groupStory = $groupStory;
        $this->user = $user;
        $this->student = $student;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.groups.index', ['groups' => $this->group->all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.groups.create',
            ['courses' => $this->course->all(), 'directions' => $this->direction->all()]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(CreateGroupRequest $request)
    {
        try {
            DB::beginTransaction();

            $group = $this->group->create([
                'name' => $request->name,
                'year' => Helper::getSchoolYear(),
                'direction_id' => $request->direction,
                'course_id' => $request->course
            ]);

            $filePath = $request->file("students")->getRealPath();

            if (($handle = fopen($filePath, "r")) !== false) {
                while (($student = fgetcsv($handle, 1000, ";")) !== false) {
                    $existStudent = $this->student->where('personal_number', '=', $student[1])->first();

                    if (!isset($existStudent)) {
                        do {
                            $login = Str::random(8);
                            $user = $this->user->where('login', '=', $login)->first();
                        } while (isset($user));

                        $user = User::create([
                            'user_type_id' => 1,
                            'rights_id' => 1,
                            'login' => $login,
                            'password' => Hash::make('password'),
                        ]);

                        $studentPartFullName = explode(' ', $student[2]);
                        $this->student->create([
                            'personal_number' => $student[1],
                            'surname' => $studentPartFullName[0],
                            'patronymic' => $studentPartFullName[2],
                            'name' => $studentPartFullName[1],
                            'group_id' => $group->id,
                            'user_id' => $user->id,
                            'status' => 1
                        ]);
                    } else {
                        $existStudent->update([
                            'group_id' => $group->id,
                            'status' => 1
                        ]);
                    }
                }
                fclose($handle);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return back()->with('status', 'Группа успешно добавлена');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('admin.groups.edit',
            ['group' => $this->group->where('id', '=', $id)->first(),
                'directions' => $this->direction->all(),
                ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function update(UpdateGroupRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $group = $this->group->where('id', '=', $id)->first();
            $group->update([
                'direction_id' => $request->direction
            ]);

            $groupStudents = $group->students()->get();
            $csvFile = $request->file('students');

            if (isset($csvFile)) {
                $filePath = $request->file("students")->getRealPath();

                if (($handle = fopen($filePath, "r")) !== false) {
                    while (($student = fgetcsv($handle, 1000, ";")) !== false) {
                        $csvStudents[] = $student;
                        $existStudent = $this->student->where('personal_number', '=', $student[1])->first();

                        if (!isset($existStudent)) {
                            $studentPartFullName = explode(' ', $student[2]);
                            do {
                                $login = Str::random(8);
                                $user = $this->user->where('login', '=', $login)->first();
                            } while (isset($user));

                            $user = User::create([
                                'user_type_id' => 1,
                                'rights_id' => 1,
                                'login' => $login,
                                'password' => Hash::make('password'),
                            ]);

                            $this->student->create([
                                'personal_number' => $student[1],
                                'surname' => $studentPartFullName[0],
                                'patronymic' => $studentPartFullName[2],
                                'name' => $studentPartFullName[1],
                                'group_id' => $group->id,
                                'user_id' => $user->id,
                                'status' => 1
                            ]);
                        } else {
                            $existStudent->update([
                                'group_id' => $group->id,
                                'status' => 1
                            ]);
                        }
                    }
                    fclose($handle);
                }
            }

            foreach($groupStudents as $groupStudent) {
                $studentExistInCsv = false;
                foreach ($csvStudents as $csvStudent) {
                    if ($groupStudent->personal_number == $csvStudent[1]) {
                        $studentExistInCsv = true;
                    }
                }

                if (!$studentExistInCsv) {
                    $groupStudent->update([
                        'status' => 0
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return back()->with('status', 'Группа успешно добавлена');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
