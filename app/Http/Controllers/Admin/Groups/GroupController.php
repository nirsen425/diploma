<?php

namespace App\Http\Controllers\Admin\Groups;

use App\Course;
use App\Direction;
use App\Group;
use App\GroupStory;
use App\Http\Requests\UpdateGroupRequest;
use App\Student;
use App\StudentGroupStory;
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
    protected $studentGroupStory;

    /**
     * GroupController constructor.
     */
    public function __construct(Course $course, Direction $direction,
                                Group $group, GroupStory $groupStory,
                                User $user, Student $student,
                                StudentGroupStory $studentGroupStory)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->course = $course;
        $this->direction = $direction;
        $this->group = $group;
        $this->groupStory = $groupStory;
        $this->user = $user;
        $this->student = $student;
        $this->studentGroupStory = $studentGroupStory;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.groups.index', ['groups' => $this->group->where('course_id', '!=', 5)->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currentYear = Helper::getSchoolYear();
        $untranslatedGroup = $this->group->where('year', '<', $currentYear)->first();

        return view('admin.groups.create',
            ['courses' => $this->course->where('id', '!=', 5)->get(),
                'directions' => $this->direction->all(),
                'currentYear' => Helper::getSchoolYear(),
                'untranslatedGroup' => $untranslatedGroup]);
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
            $currentYear = Helper::getSchoolYear();

            $untranslatedGroup = $this->group->where('year', '<', $currentYear)->first();

            if (isset($untranslatedGroup)) {
                abort(500);
            }

            $group = $this->group->create([
                'name' => $request->name,
                'year' => $currentYear,
                'direction_id' => $request->direction,
                'course_id' => $request->course
            ]);

            $groupStory = $this->groupStory->create([
                'name' => $request->name,
                'group_id' => $group->id,
                'course_id' => $request->course,
                'year_history' => $currentYear
            ]);

            $filePath = $request->file("students")->getRealPath();

            if (($handle = fopen($filePath, "r")) !== false) {
                while (($studentCsv = fgetcsv($handle, 1000, ";")) !== false) {
                    $existStudent = $this->student->where('personal_number', '=', $studentCsv[1])->first();

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

                        $studentPartFullName = explode(' ', $studentCsv[2]);
                        $studentBd = $this->student->create([
                            'personal_number' => $studentCsv[1],
                            'surname' => $studentPartFullName[0],
                            'patronymic' => $studentPartFullName[2],
                            'name' => $studentPartFullName[1],
                            'group_id' => $group->id,
                            'user_id' => $user->id
                        ]);

                        $this->studentGroupStory->create([
                            'student_id' => $studentBd->id,
                            'group_story_id' => $groupStory->id
                        ]);

                    } else {
                        $existStudentGroup = $existStudent->group()->first();
                        if ($existStudentGroup) {
                            $existStudentGroup->groupStories()
                                ->where('year_history', '=', Helper::getSchoolYear())->first()->studentGroupStory()
                                ->where('student_id', '=', $existStudent->id)->delete();
                        }

                        $existStudent->update([
                            'group_id' => $group->id,
                        ]);

                        $this->studentGroupStory->create([
                            'student_id' => $existStudent->id,
                            'group_story_id' => $groupStory->id
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
        $group = $this->group->where('id', '=', $id)->first();

        if (!isset($group) or $group->course()->first()->id == 5) {
            abort(404);
        }

        $students = $group->students()->get();
        return view('admin.groups.show', ['group' => $group,
            'students' => $students]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $group = $this->group->where('id', '=', $id)->first();
        if (!isset($group) or $group->course()->first()->id == 5) {
            abort(404);
        }
        $countGroupStoryForGroup = $group->groupStories()->count();
        return view('admin.groups.edit',
            ['group' => $this->group->where('id', '=', $id)->first(),
                'directions' => $this->direction->all(),
                'courses' => $this->course->where('id', '!=', 5)->get(),
                'countGroupStoryForGroup' => $countGroupStoryForGroup
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
            $groupStory = $group->groupStories()->where('year_history', '=', $group->year)->first();
            $countGroupStoryForGroup = $group->groupStories()->count();

            $data = [
                'name' => $request->name
            ];

            if ($countGroupStoryForGroup == 1) {
                $data['direction_id'] = $request->direction;
                $data['course_id'] = $request->course;
            }

            $group->update($data);

            if ($countGroupStoryForGroup != 1) {
                unset($data['direction_id']);
            }

            $groupStory->update($data);

            $groupStudents = $groupStory->students()->get();

            $csvFile = $request->file('students');

            if (isset($csvFile)) {
                $filePath = $request->file("students")->getRealPath();

                if (($handle = fopen($filePath, "r")) !== false) {
                    while (($studentCsv = fgetcsv($handle, 1000, ";")) !== false) {
                        $csvStudents[] = $studentCsv;

                        $existStudent = $this->student->where('personal_number', '=', $studentCsv[1])->first();

                        if (!isset($existStudent)) {
                            $studentPartFullName = explode(' ', $studentCsv[2]);
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

                            $studentBd = $this->student->create([
                                'personal_number' => $studentCsv[1],
                                'surname' => $studentPartFullName[0],
                                'patronymic' => $studentPartFullName[2],
                                'name' => $studentPartFullName[1],
                                'group_id' => $group->id,
                                'user_id' => $user->id,
                            ]);

                            $this->studentGroupStory->create([
                                'student_id' => $studentBd->id,
                                'group_story_id' => $groupStory->id
                            ]);
                        } else {
                            $groupExistStudent = $existStudent->group()->first();

                            if (!$groupExistStudent) {
                                $existStudent->update([
                                    'group_id' => $group->id,
                                ]);

                                $this->studentGroupStory->create([
                                    'student_id' => $existStudent->id,
                                    'group_story_id' => $groupStory->id
                                ]);
                            } elseif ($groupExistStudent->id != $group->id) {
                                $existStudent->group()->first()->groupStories()
                                    ->where('year_history', '=', $group->year)->first()->studentGroupStory()
                                    ->where('student_id', '=', $existStudent->id)->delete();

                                $existStudent->update([
                                    'group_id' => $group->id,
                                ]);

                                $this->studentGroupStory->create([
                                    'student_id' => $existStudent->id,
                                    'group_story_id' => $groupStory->id
                                ]);
                            }
                        }
                    }
                    fclose($handle);
                }

                foreach($groupStudents as $groupStudent) {
                    $studentExistInCsv = false;
                    foreach ($csvStudents as $csvStudent) {
                        if ($groupStudent->personal_number == $csvStudent[1]) {
                            $studentExistInCsv = true;
                        }
                    }

                    if (!$studentExistInCsv) {
                        $groupStudent->group()->first()->groupStories()
                            ->where('year_history', '=', $group->year)->first()->studentGroupStory()
                            ->where('student_id', '=', $groupStudent->id)->delete();

                        $groupStudent->applications()->where('year', '=', $group->year)
                            ->whereIn('status_id', [1, 2])->delete();

                        $groupStudent->update([
                            'group_id' => null
                        ]);
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return back()->with('status', 'Группа успешно обновлена');
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
