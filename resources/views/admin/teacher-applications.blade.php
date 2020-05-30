@extends('admin.base.base')

@section('content')
    <link href="{{ asset('css/admin/teacher-applications.css') }}" rel="stylesheet">

    <div class="teacher-application-container drop-shadow rounded">
        <div class="container-fluid p-3">
            <div class="row">
                <h3 class="col-12 col-lg-6 mt-2">Заявки руководителей</h3>
                <div class="col-12 col-lg-6 text-lg-right">
                    @php
                        // достаем year и teacherId из адресной строки
                        // "admin/teacher-applications/{year}/{teacherId}"
                        $uri = Request::path();
                        // разбиваем "/" и заносим в элементы массива
                        $uriParam = explode('/', $uri);
                        // если year и teacherId существуют, передаем их как параметры
                        // если нет, обнуляем (кнопка будет выключена)
                        if(isset($uriParam[2]) and isset($uriParam[3])) {
                            $year = $uriParam[2];
                            $teacherId = $uriParam[3];
                        }
                        else {
                            $year = null;
                            $teacherId = null;
                        }
                        //var_dump($uriParam);
                    @endphp
                    @if (isset($selectedTeacher) and (!$practiceApplications->isEmpty()))
                        <a href="{{ route('report_practice_teacher', ['year' => $year, 'teacherId' => $teacherId]) }}" class="button button-large mt-2">Сформировать отчет (по руководителю)</a>
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
                <div class="teacher text-white">
                    <a href="{{ route('lecturers.show', ['teacher' => $selectedTeacher->id]) }}" class="student-link d-block pl-3 pt-2 pb-2 mt-2">{{ $selectedTeacher->getFullName() }}</a>
                </div>
                <div class="p-3">
                    <div class="teacher-application">
                        @if (!$practiceApplications->isEmpty())
                            <table class="table">
                                <tr >
                                    <td class="student-practice text-white pl-3 pr-3 pt-1 pb-1">
                                        ФИО студента
                                    </td>
                                    <td class="student-practice text-white pr-3 pt-1 pb-1">
                                        Номер группы
                                    </td>
                                </tr>
                                @foreach($students as $student)
                                    <tr class="student-container bg-white">
                                        <td class="student">
                                            <a href="{{ route('students.show', ['student' => $student->id]) }}" class="student-link d-block p-3">{{ $student->getFullName() }}</a>
                                        </td>
                                        <td class="student">
                                            {{ $student->group()->first()->name }}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        @else
                            <div class="student p-3">Нет студентов</div>
                        @endif
                    </div>
                <!--
                <div class="student-diploma text-white pl-3 pt-1 pb-1">Студенты проходящие дипломную работу</div>
                    @php
                    // Получение одобренных преподавателем заявок на диплом
                      $diplomaApplications = $selectedTeacher->applications()->where([['year', '=', $selectedYear], ['status_id', '=', 2], ['type_id', '=', 2]])->get()
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
-->
                </div>
            @endif
        @else
            <div class="student p-3">Нет существующих заявок</div>
        @endif
    </div>

    <script src="{{ asset('js/admin/teacher-applications.js') }}"></script>
@endsection
