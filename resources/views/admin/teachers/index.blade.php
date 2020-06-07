@extends('admin.base.base')

@section('content')
    <link href="{{ asset('css/admin/teachers/teacher-index.css') }}" rel="stylesheet">
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
                    <p>Вы действительно хотите удалить преподавателя?</p>
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
                    <p>Преподаватель успешно удален</p>
                </div>
            </div>
        </div>
    </div>
    <div id="isFailure" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-content">
                <div class="modal-body">
                    <p>Не удалось удалить преподавателя</p>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-white shell p-3 text-break">
        <div class="d-flex justify-content-between">
            <h3>Список преподавателей</h3>
            <a href="{{ route('report_login_teacher')}}" class="button button-large"
               id="generate-report">Сформировать отчет по логинам</a>
        </div>
    </div>
    <table class="table teacher-table">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Фото</th>
            <th scope="col">Фамилия</th>
            <th scope="col">Имя</th>
            <th scope="col">Отчество</th>
            <th scope="col">Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($teachers as $teacher)
            <tr>
                <td><img src="{{ asset("storage/images/" . $teacher->photo) }}" class="teacherPhoto" alt="Преподаватель"></td>
                <td>{{ $teacher->surname }}</td>
                <td>{{ $teacher->name }}</td>
                <td>{{ $teacher->patronymic }}</td>
                <td>
                    <a href="{{ route('lecturers.show', ['teacher' => $teacher->id]) }}">
                        <i class="far fa-eye"></i>
                    </a>
                    <a href="{{ route('lecturers.edit', ['teacher' => $teacher->id]) }}">
                        <i class="far fa-edit"></i>
                    </a>
                    <a href="#" class="delete" teacher-id="{{ $teacher->id }}" data-toggle="modal" data-target="#confirmDelete">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <script src="{{ asset('js/admin/teachers/teacher-delete.js') }}"></script>
@endsection
