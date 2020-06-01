<?php

namespace Tests\Feature\Http\Controllers;

use App\Group;
use App\Helpers\Helper;
use App\Student;
use App\Teacher;
use App\User;
use App\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApplicationControllerTests extends TestCase
{
    use DatabaseTransactions;

    private $studentUser;
    private $student;
    private $teacherUser;
    private $teacher;

    public function setUp(): void
    {
        parent::setUp();
        $group = factory(Group::class)->create();

        $studentUser = factory(User::class)->states('student')->create();

        $studentUser->student()->save($student = factory(Student::class)->make([
            'group_id' => $group->id
        ]));

        $teacherUser = factory(User::class)->states('teacher')->create();
        $teacherUser->teacher()->save($teacher = factory(Teacher::class)->make());

        $this->studentUser = $studentUser;
        $this->student = $student;
        $this->teacherUser = $teacherUser;
        $this->teacher = $teacher;
    }

    /**
     * Проверка отправки заявки, если у студента
     */
    public function testStoreApplicationWaitAndConfirmNotExist()
    {
        $teacherId = $this->teacher->id;
        $typeId = 1;
        $this->actingAs($this->studentUser)
            ->json('POST', '/application/' . $teacherId, ['type_id' => $typeId]);

        $this->assertDatabaseHas('applications', [
            'student_id' => $this->student->id,
            'teacher_id' => $this->teacher->id,
            'type_id' => $typeId,
            'year' => Helper::getSchoolYear(),
            'status_id' => 1
        ]);
    }

    public function testStoreApplicationWaitExist()
    {
        $teacherId = $this->teacher->id;
        $typeId = 1;

        Application::create([
            'student_id' => $this->student->id,
            'teacher_id' => $this->teacher->id,
            'type_id' => $typeId,
            'year' => Helper::getSchoolYear(),
            'status_id' => 1
        ]);

        $this->actingAs($this->studentUser)
            ->json('POST', '/application/' . $teacherId, ['type_id' => $typeId]);

        $countApplications = Application::where([
            ['student_id', '=', $this->student->id],
            ['teacher_id', '=', $this->teacher->id],
            ['type_id', '=', $typeId],
            ['year', '=', Helper::getSchoolYear()],
            ['status_id', '=', 1]
        ])->count();

        $this->assertEquals(1, $countApplications);
    }

    public function testStoreApplicationConfirmExist()
    {
        $teacherId = $this->teacher->id;
        $typeId = 1;

        Application::create([
            'student_id' => $this->student->id,
            'teacher_id' => $this->teacher->id,
            'type_id' => $typeId,
            'year' => Helper::getSchoolYear(),
            'status_id' => 2
        ]);

        $this->actingAs($this->studentUser)
            ->json('POST', '/application/' . $teacherId, ['type_id' => $typeId]);

        $countApplications = Application::where([
            ['student_id', '=', $this->student->id],
            ['teacher_id', '=', $this->teacher->id],
            ['type_id', '=', $typeId],
            ['year', '=', Helper::getSchoolYear()],
            ['status_id', '=', 1]
        ])->count();

        $this->assertEquals(0, $countApplications);
    }

    public function testStoreApplicationRejectExist()
    {
        $teacherId = $this->teacher->id;
        $typeId = 1;

        Application::create([
            'student_id' => $this->student->id,
            'teacher_id' => $this->teacher->id,
            'type_id' => $typeId,
            'year' => Helper::getSchoolYear(),
            'status_id' => 3
        ]);

        $this->actingAs($this->studentUser)
            ->json('POST', '/application/' . $teacherId, ['type_id' => $typeId]);

        $countApplications = Application::where([
            ['student_id', '=', $this->student->id],
            ['teacher_id', '=', $this->teacher->id],
            ['type_id', '=', $typeId],
            ['year', '=', Helper::getSchoolYear()],
            ['status_id', '=', 3]
        ])->count();

        $this->assertEquals(1, $countApplications);
    }

    public function testConfirm()
    {
        $typeId = 1;
        $studentId = $this->student->id;

        Application::create([
            'student_id' => $this->student->id,
            'teacher_id' => $this->teacher->id,
            'type_id' => $typeId,
            'year' => Helper::getSchoolYear(),
            'status_id' => 1
        ]);

        $this->actingAs($this->teacherUser)
            ->json('POST', '/application/confirm/' . $studentId . '/' . $typeId);

        $this->assertDatabaseHas('applications', [
            'student_id' => $studentId,
            'teacher_id' => $this->teacher->id,
            'type_id' => $typeId,
            'year' => Helper::getSchoolYear(),
            'status_id' => 2
        ]);
    }

    public function testReject()
    {
        $typeId = 1;
        $studentId = $this->student->id;

        Application::create([
            'student_id' => $this->student->id,
            'teacher_id' => $this->teacher->id,
            'type_id' => $typeId,
            'year' => Helper::getSchoolYear(),
            'status_id' => 1
        ]);

        $this->actingAs($this->teacherUser)
            ->json('POST', '/application/reject/' . $studentId . '/' . $typeId);

        $this->assertDatabaseHas('applications', [
            'student_id' => $studentId,
            'teacher_id' => $this->teacher->id,
            'type_id' => $typeId,
            'year' => Helper::getSchoolYear(),
            'status_id' => 3
        ]);
    }

    public function testCancel()
    {
        $typeId = 1;
        $teacherId = $this->teacher->id;

        Application::create([
            'student_id' => $this->student->id,
            'teacher_id' => $this->teacher->id,
            'type_id' => $typeId,
            'year' => Helper::getSchoolYear(),
            'status_id' => 1
        ]);

        $this->actingAs($this->studentUser)
            ->json('POST', '/application/cancel/' . $teacherId . '/' . $typeId);

        $this->assertDatabaseMissing('applications', [
            'student_id' => $this->student->id,
            'teacher_id' => $teacherId,
            'type_id' => $typeId,
            'year' => Helper::getSchoolYear(),
            'status_id' => 1
        ]);
    }
}
