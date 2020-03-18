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

{{--    <div class="content drop-shadow">--}}
    <div>
        <label for="year" class="font-weight-bold year">Год</label>
        <div class="d-flex">
            <input type="text" class="mb-2 text-center" name="year">
            <button class="button button-large mb-2 ml-2" id="select-all-teachers">Отметить всех</button>
        </div>
        <table class="table" id="teacher-limits-table">
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
                    <tr>
                        <td class="d-none">{{ $teacher->id }}</td>
                        <td class="align-bottom">
                            <input type="checkbox" class="large-checkbox" name="change">
                        </td>
                        <td class="align-bottom">{{ $teacher->getFullName() }}</td>
                        <td class="align-bottom">
                            <input type="text" name="limit" class="limit text-center">
                        </td>
                        <td>
                            <div class="d-flex">
                                <div class="d-flex flex-column">
                                    <label for="first_course">1</label>
                                    <input type="checkbox" class="medium-checkbox course-selector" name="first_course">
                                </div>
                                <div class="d-flex flex-column ml-1">
                                    <label for="second_course">2</label>
                                    <input type="checkbox" class="medium-checkbox course-selector" name="second_course">
                                </div>
                                <div class="d-flex flex-column ml-1">
                                    <label for="third_course">3</label>
                                    <input type="checkbox" class="medium-checkbox course-selector" name="third_course">
                                </div>
                                <div class="d-flex flex-column ml-1">
                                    <label for="fourth_course">4</label>
                                    <input type="checkbox" class="medium-checkbox course-selector" name="fourth_course">
                                </div>
                                <div class="d-flex flex-column ml-1">
                                    <label for="all_course">Все</label>
                                    <input type="checkbox" class="align-self-center medium-checkbox all-course-selector" name="all_course">
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button class="button button-large mt-1" id="change-limits">Изменить</button>
    </div>

    <script src="{{ asset('js/admin/teacher-limits.js') }}"></script>
@endsection
