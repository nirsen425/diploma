@extends('admin.base.base')

@section('content')
    <link href="{{ asset('css/admin/student-applications.css') }}" rel="stylesheet">

    <div class="bg-white shell p-3" >
      <div class="container-fluid p-3">
            <div class="row">
                <h3 class="col-12 col-lg-6 mt-2">Заявки студентов</h3>
                <div class="col-12 col-lg-6 text-lg-right">
                    @if (isset($selectedGroup))
                        <a href="{{ route('report_practice_group', ['year' => $historyYear, 'groupStoryId' => $selectedGroup]) }}" class="button button-large mt-2" id="student-report">Сформировать отчет (по группе)</a>
                    @else
                        <a href="#" class="button button-large disabled mt-2">Сформировать отчет (по группе)</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="d-flex pb-3 pr-3 ml-3">
            <div class="d-flex flex-column">
                <label for="year" class="font-weight-bold year">Год</label>
                <select class="mb-2 text-center" name="year">
                    @if (isset($yearsGroup))
                        @foreach($yearsGroup as $yearGroup)
                            <option {{ $yearGroup == $historyYear ? "selected" : "" }}>{{ $yearGroup }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="d-flex flex-column ml-2">
                <label for="group" class="font-weight-bold group">Группа</label>
                <select class="mb-2" name="group">
                    @if (isset($groupsBySelectedYear))
                        @foreach($groupsBySelectedYear as $groupBySelectedYear)
                            @if (!isset($selectedGroup))
                                <option class="d-none" selected></option>
                            @endif
                            <option value="{{ $groupBySelectedYear->id }}" {{ (isset($selectedGroup) and $groupBySelectedYear->id == $selectedGroup->id) ? "selected" : "" }}>{{ $groupBySelectedYear->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        @if (isset($selectedGroup) and !$students->isEmpty())
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
                    @foreach($students as $student)
                        @php
                            // Очищаем переменные в новой итерации, так как они могут повлиять на нее
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
                                <select name="teacher">
                                    <option class="d-none"></option>
                                    @if(!empty($teachers))
                                        @foreach($teachers as $teacher)
                                            <option {{ (isset($applicationTeacherId) and $applicationTeacherId == $teacher->id) ? 'selected' : ''}} value="{{ $teacher->id }}">{{ $teacher->getFullName() }}</option>
                                        @endforeach
                                    @endif
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
        @else
            <div class="student-empty p-3">Нет студентов</div>
        @endif
    </div>

    <script src="{{ asset('js/admin/student-applications.js') }}"></script>
@endsection

