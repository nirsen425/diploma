@extends('admin.base.base')

@section('content')
    <link href="{{ asset('css/admin/student-applications.css') }}" rel="stylesheet">

    <div class="bg-white p-3" >
        <div class="d-flex">
            <div class="d-flex flex-column">
                <label for="year" class="font-weight-bold year">Год</label>
                <select class="mb-2 text-center" name="year">
                    @foreach($yearsGroupStories as $yearsGroupStory)
                        <option {{ $yearsGroupStory->year_history == $historyYear ? "selected" : "" }}>{{ $yearsGroupStory->year_history }}</option>
                    @endforeach
                </select>
            </div>
            <div class="d-flex flex-column ml-2">
                <label for="group" class="font-weight-bold group">Группа</label>
                <select class="mb-2" name="group">
                    @if (isset($groupStories))
                        @foreach($groupStories as $groupStory)
                            @if (!isset($currentGroupStory))
                                <option  class="d-none" selected></option>
                            @endif
                            <option value="{{ $groupStory->id }}" {{ (isset($currentGroupStory) and $groupStory->id == $currentGroupStory->id) ? "selected" : "" }}>{{ $groupStory->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        @if (isset($currentGroupStory))
            <table class="table bg-light" id="student-applications-table">
                <thead class="thead-dark">
                    <tr>
                        <th class="d-none">id</th>
                        <th scope="col" class="pl-4">ФИО студента</th>
                        <th scope="col" class="pl-4">ФИО преподавателя</th>
                        <th scope="col" class="pl-4">Статус заявки</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $groups = $currentGroupStory->group()->first();
                        $students = $groups->students()->get();
                    @endphp
                    @foreach($students as $student)
                        @php
                            unset($applicationTeacherId);
                            unset($currentApplicationStatusId);
                            $application = $student->applications()->where('year', '=', $historyYear)->get()->last();
                            if (isset($application)) {
                                $applicationTeacherId = $application->teacher()->value('id');
                                $currentApplicationStatusId = $application->status_id;
                            }
                        @endphp
                        <tr>
                            <td class="d-none student-id">{{ $student->id }}</td>
                            <td class="align-bottom p-4">{{ $student->getFullName() }}</td>
                            <td class="align-bottom p-4">
                                @php
                                    $teachers = $teacherModel->getTeachersByCourseForCurrentYear($student->group()->value('course'));
                                @endphp
                                <select name="teacher">
                                    <option class="d-none"></option>
                                    @foreach($teachers as $teacher)
                                        <option {{ (isset($applicationTeacherId) and $applicationTeacherId == $teacher->id) ? 'selected' : ''}} value="{{ $teacher->id }}">{{ $teacher->getFullName() }}</option>
                                    @endforeach
                                </select>
                            </td>

                            <td class="align-bottom p-4">
                                <select name="status">
                                    @if (isset($application))
                                        <option class="d-none"></option>
                                        @foreach($applicationStatuses as $applicationStatus)
                                            <option {{ (isset($currentApplicationStatusId) and $currentApplicationStatusId == $applicationStatus->id) ? 'selected' : ''}} value="{{ $applicationStatus->id }}">{{ $applicationStatus->title }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <script src="{{ asset('js/admin/student-applications.js') }}"></script>
@endsection

