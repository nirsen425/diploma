<?php

namespace App\Http\Controllers\Admin;

use App\Group;
use App\GroupStory;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\StudentGroupStory;
use Illuminate\Http\Request;
use DB;

class AdminGroupTransferController extends Controller
{
    protected $group;
    protected $groupStory;
    protected $studentGroupStory;

    public function __construct(Group $group, GroupStory $groupStory, StudentGroupStory $studentGroupStory)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->group = $group;
        $this->groupStory = $groupStory;
        $this->studentGroupStory = $studentGroupStory;
    }

    public function showGroupTransferPage()
    {
        $currentStudyYear = Helper::getSchoolYear();
        return view('admin.group-transfer', ['groups' => $this->group->where('course_id', '!=', 5)->get(),
            'currentStudyYear' => $currentStudyYear]);
    }

    public function transferGroup(Request $request)
    {
        try {
            DB::beginTransaction();

            if ($this->group->first()->year < Helper::getSchoolYear()) {

                foreach ($request->groupsArray as $newGroup) {
                    $oldGroup = $this->group->where('id', '=', $newGroup['group_id'])->first();
                    $groupStory = $this->groupStory->create([
                        'group_id' => $oldGroup['id'],
                        'course_id' => $oldGroup['course_id'],
                        'name' => $oldGroup['name'],
                        'year_history' => $oldGroup['year']
                    ]);

                    $newCourseId = $oldGroup->course()->first()->id + 1;
                    $newYear = $oldGroup->year + 1;

                    if ($newCourseId <= 5) {
                        $oldGroup->update([
                            'name' => $newGroup['new_name'],
                            'course_id' => $newCourseId,
                            'year' => $newYear
                        ]);
                    }

                    foreach ($oldGroup->students()->get() as $oldStudent) {
                        if ($newCourseId == 5) {
                            $oldStudent->update(['status' => 0]);
                        }

                        $this->studentGroupStory->create([
                            'student_id' => $oldStudent['id'],
                            'group_story_id' => $groupStory['id']
                        ]);
                    }

                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return "true";
    }
}
