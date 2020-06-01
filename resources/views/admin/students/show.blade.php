@extends('admin.base.base')

@section('content')
    <link href="{{ asset('css/admin/students/student-show.css') }}" rel="stylesheet">
    <div class="content text-break">
        <h3 class="mb-3">Просмотр студента</h3>
        <div class="font-weight-bold">ФИО</div>
        <div>{{ $student->getFullName() }}</div>
        <div class="font-weight-bold">Логин</div>
        <div>{{ $student->user()->value("login") }}</div>
        <div class="font-weight-bold">Права</div>
        @php
            $rights = $student->user()->value("rights_id");
        @endphp
        @if ($rights == 1)
            Обычные
        @elseif ($rights == 2)
            Администратор
        @endif
        <div class="font-weight-bold">E-mail</div>
        <div>{{ $student->email }}</div>
        <div class="font-weight-bold">Статус</div>
        @if ($student->status)
            Учится
        @else
            Отчислен/Выпущен
        @endif
        <div class="font-weight-bold">Группа</div>
        <div><a href="#">{{ $student->group()->value('name') }}</a></div>
    </div>
    <script src="{{ asset('js/admin/students/student-active-submenu.js') }}"></script>
@endsection
