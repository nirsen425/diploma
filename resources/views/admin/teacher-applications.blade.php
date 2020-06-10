@extends('admin.base.base')

@section('content')
    <link href="{{ asset('css/admin/teacher-applications.css') }}" rel="stylesheet">

    <div class="bg-white shell p-3">
        <div class="container-fluid p-3">
            <div class="row">
                <h3 class="col-12 col-lg-6 mt-2">Заявки руководителей</h3>
                <div class="col-12 col-lg-6 text-lg-right">
                    @if (isset($selectedTeacher) and (!$practiceApplications->isEmpty()))
                        <a href="{{ route('report_practice_teacher', ['year' => $selectedYear, 'teacherId' => $selectedTeacher]) }}" class="button button-large mt-2">Сформировать отчет (по руководителю)</a>
                    @else
                        <a href="#" class="button button-large disabled mt-2">Сформировать отчет (по руководителю)</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="d-flex pl-3 pb-3 pr-3">
            <div class="d-flex flex-column">
                <label for="year" class="font-weight-bold year">Год</label>
                <select class="mb-2 text-center" name="year">
                    @foreach($yearsApplications as $yearsApplication)
                        <option {{ $yearsApplication->year == $selectedYear ? "selected" : "" }}>{{ $yearsApplication->year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="d-flex flex-column ml-2">
                <label for="teacher-select" class="teacher-select font-weight-bold group">Руководитель</label>
                <select class="mb-2" name="teacher-select">
                    @if(isset($teachersBySelectedYear))
                        @foreach($teachersBySelectedYear as $teacherBySelectedYear)
                            @if(!isset($selectedTeacher))
                                <option class="d-none" selected></option>
                            @endif
                            <option value="{{ $teacherBySelectedYear->id }}" {{ (isset($selectedTeacher) and $teacherBySelectedYear->id == $selectedTeacher->id) ? "selected" : "" }}> {{ $teacherBySelectedYear->getFullName() }} </option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>

        @if (isset($yearsApplication))
            @if (isset($selectedTeacher))
                @if (!$practiceApplications->isEmpty())
                    <table class="table bg-light">
                        <thead class="thead-dark">
                        <tr>
                            <th class="d-none">id</th>
                            <th scope="col" class="pl-4">ФИО студента</th>
                            <th scope="col" class="pl-4">Номер группы</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($students as $student)
                            <tr>
                                <td class="d-none student-id">{{ $student->id }}</td>
                                <td class="align-bottom p-4">{{ $student->getFullName() }}</td>
                                <td class="align-bottom p-4">{{ $student->group()->first()->groupStories()->where('year_history', '=', $selectedYear)->first()->name }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="student p-3">Нет студентов</div>
                @endif
            @endif
        @else
            <div class="student p-3">Нет существующих заявок</div>
        @endif
    </div>

    <script src="{{ asset('js/admin/teacher-applications.js') }}"></script>
@endsection
