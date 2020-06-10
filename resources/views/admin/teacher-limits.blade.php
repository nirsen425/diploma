@extends('admin.base.base')

@section('content')
    <link href="{{ asset('css/admin/teacher-limits.css') }}" rel="stylesheet">

    <div id="emptyYear" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-body">
                    Введите год
                </div>
                <div class="modal-footer">
                    <button type="button" class="button" data-dismiss="modal">Ок</button>
                </div>
            </div>
        </div>
    </div>

    <div id="emptyLimit" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    Не все лимиты заполнены
                </div>
                <div class="modal-footer">
                    <button type="button" class="button" data-dismiss="modal">Ок</button>
                </div>
            </div>
        </div>
    </div>

    <div id="notSelectTeacher" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    Выберите преподавателей
                </div>
                <div class="modal-footer">
                    <button type="button" class="button" data-dismiss="modal">Ок</button>
                </div>
            </div>
        </div>
    </div>

    <div id="change-success" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    Лимиты успешно добавлены
                </div>
                <div class="modal-footer">
                    <button type="button" class="button" data-dismiss="modal">Ок</button>
                </div>
            </div>
        </div>
    </div>

    <div id="year-exist" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    Такой период уже существует
                </div>
                <div class="modal-footer">
                    <button type="button" class="button" data-dismiss="modal">Ок</button>
                </div>
            </div>
        </div>
    </div>

    <div id="year-empty" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    Новый период не заполнен
                </div>
                <div class="modal-footer">
                    <button type="button" class="button" data-dismiss="modal">Ок</button>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white shell p-3 text-break">
        <h3 class="mb-3">Лимиты преподавателей</h3>
        @if (!$teachers->isEmpty())
        <div class="d-flex flex-column flex-lg-row ">
            <div class="d-flex">
                <div class="d-flex flex-column">
                    <label for="year" class="font-weight-bold year">Год</label>
                    <select class="mb-2 text-center" name="year">
                        @foreach($limitYears as $limitYear)
                            <option {{ $limitYear->year == $year ? "selected" : "" }}>{{ $limitYear->year }}</option>
                        @endforeach
                        @if (!$yearExist)
                            <option selected>{{ $year }}</option>
                        @endif
                    </select>
                </div>
                <button class="button button-large align-self-end mb-2 ml-2" id="select-all-teachers">Отметить всех</button>
            </div>
            <div class="d-flex">
                <div class="d-flex flex-column ml-lg-2">
                    <label for="year" class="font-weight-bold year">Новый период</label>
                    <input type="text" class="mb-2 text-center" name="new-year">
                </div>
                <button class="button button-large align-self-end mb-2 ml-2" id="add-new-year">Добавить период</button>
            </div>
        </div>
        <table class="table bg-light" id="teacher-limits-table">
            <thead class="thead-dark">
                <tr>
                    <th class="d-none">id</th>
                    <th>Изменить</th>
                    <th scope="col">ФИО</th>
                    <th scope="col">Лимит</th>
                    <th scope="col">Курсы</th>
                </tr>
            </thead>
            <tbody>
                @foreach($teachers as $teacher)
                    @php
                        $teacherLimit = $teacher->currentLimits()->where('year', '=', $year)->first();
                    @endphp
                    <tr>
                        <td class="d-none">{{ $teacher->id }}</td>
                        <td class="align-bottom">
                            <input type="checkbox" class="large-checkbox" name="change" {{ isset($teacherLimit) ? "checked" : "" }}>
                        </td>
                        <td class="align-bottom">{{ $teacher->getFullName() }}</td>
                        <td class="align-bottom">
                            <input type="text" name="limit" class="limit text-center" {{ isset($teacherLimit->limit) ? "value=$teacherLimit->limit" : "" }}>
                        </td>
                        <td>
                            <div class="d-flex">
                                <div class="d-flex flex-column">
                                    <label for="first_course">1</label>
                                    <input type="checkbox" class="medium-checkbox course-selector" name="first_course" {{ (isset($teacherLimit) and $teacherLimit->first_course) ? "checked" : "" }}>
                                </div>
                                <div class="d-flex flex-column ml-1">
                                    <label for="second_course">2</label>
                                    <input type="checkbox" class="medium-checkbox course-selector" name="second_course" {{ (isset($teacherLimit) and $teacherLimit->second_course) ? "checked" : "" }}>
                                </div>
                                <div class="d-flex flex-column ml-1">
                                    <label for="third_course">3</label>
                                    <input type="checkbox" class="medium-checkbox course-selector" name="third_course" {{ (isset($teacherLimit) and $teacherLimit->third_course) ? "checked" : "" }}>
                                </div>
                                <div class="d-flex flex-column ml-1">
                                    <label for="fourth_course">4</label>
                                    <input type="checkbox" class="medium-checkbox course-selector" name="fourth_course" {{ (isset($teacherLimit) and $teacherLimit->fourth_course) ? "checked" : "" }}>
                                </div>
                                <div class="d-flex flex-column ml-1">
                                    <label for="all_course">Все</label>
                                    <input type="checkbox" class="align-self-center medium-checkbox all-course-selector {{ (isset($teacherLimit) and $teacherLimit->first_course and $teacherLimit->second_course and $teacherLimit->third_course and $teacherLimit->fourth_course) ? "active" : "" }}" name="all_course" {{ (isset($teacherLimit) and $teacherLimit->first_course and $teacherLimit->second_course and $teacherLimit->third_course and $teacherLimit->fourth_course) ? "checked" : "" }}>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button class="button button-large mt-1" id="change-limits">Изменить</button>
        @else
            <h4 class="bg-info p-2 text-white mt-3">Нет преподавателей</h4>
        @endif
    </div>

    <script src="{{ asset('js/admin/teacher-limits.js') }}"></script>
@endsection
