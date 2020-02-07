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
                    <button type="button" class="btn btn-danger confirm-delete" data-dismiss="modal">Да</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Отмена</button>
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
            <th scope="col">Студенческий билет</th>
            <th scope="col">Имя</th>
            <th scope="col">Отчество</th>
            <th scope="col">Фамилия</th>
            <th scope="col">Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($students as $student)
            <tr>
                <td>{{ $student->student_ticket }}</td>
                <td>{{ $student->name }}</td>
                <td>{{ $student->patronymic }}</td>
                <td>{{ $student->surname }}</td>
                <td>
                    <a href="{{ route('students.show', ['student' => $student->id]) }}">
                        <i class="far fa-eye"></i>
                    </a>
                    <a href="{{ route('students.edit', ['student' => $student->id]) }}">
                        <i class="far fa-edit"></i>
                    </a>
                    <a href="#" class="delete" student-id="{{ $student->id }}" data-toggle="modal" data-target="#confirmDelete">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <script src="{{ asset('js/admin/students/student-active-submenu.js') }}"></script>
    <script src="{{ asset('js/admin/students/student-delete.js') }}"></script>
@endsection