@extends('admin.base.base')

@section('content')
    <link href="{{ asset('css/admin/students/student-index.css') }}" rel="stylesheet">
    <div id="confirmDelete" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Подтверждение удаления</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Вы действительно хотите удалить студента?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn confirm-delete" data-dismiss="modal">Да</button>
                    <button type="button" class="btn" data-dismiss="modal">Отмена</button>
                </div>
            </div>
        </div>
    </div>
    <div id="isDeleted" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Успех</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Студент успешно удален</p>
                </div>
            </div>
        </div>
    </div>
    <div id="isFailure" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Неудача</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Не удалось удалить студента</p>
                </div>
            </div>
        </div>
    </div>
    <table class="table student-table">
        <thead class="thead-dark">
        <tr>
            <th scope="col" class="pl-4">Фамилия</th>
            <th scope="col" class="pl-4">Имя</th>
            <th scope="col" class="pl-4">Отчество</th>
            <th scope="col" class="pl-4">Группа</th>
            <th scope="col" class="pl-4">Статус</th>
            <th scope="col" class="pl-4">Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($students as $student)
            <tr>
                <td class="p-4">{{ $student->surname }}</td>
                <td class="p-4">{{ $student->name }}</td>
                <td class="p-4">{{ $student->patronymic }}</td>
                @if ($student->group()->value('name'))
                    <td class="p-4">{{ $student->group()->value('name') }}</td>
                @else
                    <td class="p-4">Нет</td>
                @endif
                @if ($student->group_id)
                    <td class="p-4">Учится</td>
                @else
                    <td class="p-4">Отчислен/Выпущен</td>
                @endif
                <td class="p-4">
                    <a href="{{ route('students.show', ['student' => $student->id]) }}">
                        <i class="far fa-eye"></i>
                    </a>
                    <a href="{{ route('students.edit', ['student' => $student->id]) }}">
                        <i class="far fa-edit"></i>
                    </a>
<!--
                    <a href="#" class="delete" student-id="{{ $student->id }}" data-toggle="modal" data-target="#confirmDelete">
                        <i class="fas fa-trash-alt"></i>
                    </a>
-->
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <script src="{{ asset('js/admin/students/student-active-submenu.js') }}"></script>
    <script src="{{ asset('js/admin/students/student-delete.js') }}"></script>
@endsection
