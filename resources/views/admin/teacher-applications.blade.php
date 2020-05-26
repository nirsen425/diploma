@extends('admin.base.base')

@section('content')
    <link href="{{ asset('css/admin/teacher-applications.css') }}" rel="stylesheet">

    <div class="teacher-application-container drop-shadow rounded">
        <div class="container-fluid p-3">
            <div class="row">
                <h3 class="col-12 col-lg-6 mt-2">Студенты преподавателей</h3>
                <div class="col-12 col-lg-6 text-lg-right">
                    <a href="{{ route('report_practice_group') }}" class="button button-large mt-2">Сформировать отчет (по группе)</a>
                    <a href="{{ route('report_practice_teacher') }}" class="button button-large mt-2">Сформировать отчет (по преподавателю)</a>
                </div>
            </div>
        </div>
        @foreach($teachers as $teacher)
            <div class="teacher text-white">
                <a href="{{ route('lecturers.show', ['teacher' => $teacher->id]) }}" class="student-link d-block pl-3 pt-2 pb-2 mt-2">{{ $teacher->getFullName() }}</a>
            </div>
            <div class="p-3">
                <div class="teacher-application">
                    <div class="student-practice text-white pl-3 pt-1 pb-1">Студенты на практике</div>
                    @php
                        // Получение одобренных преподавателем заявок на практику
                        $practiceApplications = $teacher->applications()->where([['status_id', '=', 2], ['type_id', '=', 1]])->get()
                    @endphp
                    <div class="student-container bg-white">
                        @if (!$practiceApplications->isEmpty())
                            @foreach($practiceApplications as $practiceApplication)
                                <div class="student">
                                    <a href="{{ route('students.show', ['student' => $practiceApplication->student()->value('id')]) }}" class="student-link d-block p-3">{{ $practiceApplication->student()->first()->getFullName() }}</a>
                                </div>
                            @endforeach
                        @else
                            <div class="student p-3">Нет студентов</div>
                        @endif
                    </div>
                    <div class="student-diploma text-white pl-3 pt-1 pb-1">Студенты проходящие дипломную работу</div>
                    @php
                        // Получение одобренных преподавателем заявок на диплом
                        $diplomaApplications = $teacher->applications()->where([['status_id', '=', 2], ['type_id', '=', 2]])->get()
                    @endphp
                    <div class="student-container bg-white">
                        @if (!$practiceApplications->isEmpty())
                            @foreach($diplomaApplications as $diplomaApplication)
                                <div class="student">
                                    <a href="{{ route('students.show', ['student' => $diplomaApplication->student()->value('id')]) }}" class="student-link d-block p-3">{{ $diplomaApplication->student()->first()->getFullName() }}</a>
                                </div>
                            @endforeach
                        @else
                            <div class="student p-3">Нет студентов</div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
