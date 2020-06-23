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
    <div class="bg-white bg-light shell p-3">
        <h3>Список страниц</h3>
        @if (!$pages->isEmpty())
            <table class="table page-table bg-light mt-3">
                <thead class="thead-dark">
                <tr>
                    <th scope="col" class="pl-4">Заголовок</th>
                    <th scope="col" class="pl-4">Показана на сайте</th>
                    <th scope="col" class="pl-4">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($pages as $page)
                    <tr>
                        <td class="p-4">{{ $page->title }}</td>
                        <td class="p-4">{{ $page->show ? "Да" : "Нет" }}</td>
                        <td class="p-4">
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
        @else
            <h4 class="bg-info p-2 text-white">Нет страниц</h4>
        @endif
    </div>

    <script src="{{ asset('js/admin/pages/page-active-submenu.js') }}"></script>
    <script src="{{ asset('js/admin/pages/page-delete.js') }}"></script>
@endsection
