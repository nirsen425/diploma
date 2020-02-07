@extends('admin.base.base')

@section('content')
    <link href="{{ asset('css/admin/pages/page-index.css') }}" rel="stylesheet">
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
                    <p>Вы действительно хотите удалить страницу?</p>
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
                    <p>Страница успешно удален</p>
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
                    <p>Не удалось удалить страницу</p>
                </div>
            </div>
        </div>
    </div>
    <table class="table page-table">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Заголовок</th>
            <th scope="col">Показана на сайте</th>
            <th scope="col">Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($pages as $page)
            <tr>
                <td>{{ $page->title }}</td>
                <td>{{ $page->show ? "Да" : "Нет" }}</td>
                <td>
                    <a href="{{ route('pages.show', ['page' => $page->id]) }}">
                        <i class="far fa-eye"></i>
                    </a>
                    <a href="{{ route('pages.edit', ['page' => $page->id]) }}">
                        <i class="far fa-edit"></i>
                    </a>
                    <a href="#" class="delete" page-id="{{ $page->id }}" data-toggle="modal" data-target="#confirmDelete">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <script src="{{ asset('js/admin/pages/page-active-submenu.js') }}"></script>
    <script src="{{ asset('js/admin/pages/page-delete.js') }}"></script>
@endsection
