@extends('admin.base.base')

@section('content')
    <div class="content">
        <h3 class="mb-3">Просмотр студента</h3>
        <h5 class="mt-0">{{ $student->name . " " . $student->patronymic . " " . $student->surname }}</h5>
        <div class="font-weight-bold">Логин</div>
        <div>{{ $student->user()->value("login") }}</div>
    </div>
    <script src="{{ asset('js/admin/students/student-active-submenu.js') }}"></script>
@endsection
