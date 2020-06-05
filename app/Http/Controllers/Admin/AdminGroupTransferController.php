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

                    $newCourseId = $oldGroup->course()->first()->id + 1;
                    $newYear = $oldGroup->year + 1;

                    $studentsOldGroup = $oldGroup->groupStories()->where('year_history', '=', $oldGroup->year)
                        ->first()->students()->get();

                    if ($newCourseId < 5) {
                        $groupStory = $this->groupStory->create([
                            'group_id' => $oldGroup['id'],
                            'course_id' => $newCourseId,
                            'name' => $newGroup['new_name'],
                            'year_history' => $newYear
                        ]);

                        foreach ($studentsOldGroup as $studentOldGroup) {
                            $this->studentGroupStory->create([
                                'student_id' => $studentOldGroup->id,
                                'group_story_id' => $groupStory->id
                            ]);
                        }
                    } else {
                        foreach ($studentsOldGroup as $studentOldGroup) {
                            $studentOldGroup->update([
                                'group_id' => null
                            ]);
                        }
                    }

                    $oldGroup->update([
                        'year' => $newYear,
                        'course_id' => $newCourseId,
                        'name' => $newGroup['new_name']
                    ]);
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
