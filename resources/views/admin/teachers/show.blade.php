@extends('admin.base.base')

@section('content')
    <link href="{{ asset('css/admin/teachers/teacher-show.css') }}" rel="stylesheet">
    <div class="content">
        <h3 class="mb-3">Просмотр преподавателя</h3>
        <div class="media">
            <img src="{{ asset("storage/images/" . $teacher->photo) }}" class="mr-3" alt="Преподаватель" style="width: 200px; height: 200px">
            <div class="media-body">
                <div class="font-weight-bold">ФИО</div>
                <div>{{ $teacher->getFullName() }}</div>
                <div class="font-weight-bold">Логин</div>
                <div>{{ $teacher->user()->value("login") }}</div>
                <div class="font-weight-bold">Права</div>
                @php
                    $rights = $teacher->user()->value("rights_id");
                @endphp
                @if ($rights == 1)
                    Обычные
                @elseif ($rights == 2)
                    Администратор
                @endif
                <div class="font-weight-bold">Краткое описание</div>
                <div>{{ $teacher->short_description }}</div>
                <div class="font-weight-bold">Полное описание</div>
                <div>{!! $teacher->full_description !!}</div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/admin/teachers/teacher-active-submenu.js') }}"></script>
@endsection
