@extends('admin.base.base')

@section('content')
    <link href="{{ asset('css/admin/students/student-update.css') }}" rel="stylesheet">
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div class="content">
        <h3 class="mb-4">Редактирование студента</h3>
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    {{ $error }}
                    <br>
                @endforeach
            </div>
        @endif
        <form action="{{ route('students.update', ['student' => $student->id]) }}" method="POST" id="studentRegistration">
            @csrf
            <input type="hidden" name="_method" value="PUT">

            <div class="form-group row ">
                <label for="login" class="col-lg-2 col-form-label font-weight-bold">Логин</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" id="login" name="login" user-id="{{ $student->user()->value('id') }}" value="{{ $student->user()->value('login') }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="password" class="col-lg-2 col-form-label font-weight-bold">Пароль</label>
                <div class="col-lg-10">
                    <button type="button" class="btn btn-secondary" id="changePassword">Сменить пароль</button>
                    <input type="password" class="form-control d-none mt-2" id="password" name="password">
                </div>
            </div>
            <div class="form-group row">
                <label for="name" class="col-lg-2 col-form-label font-weight-bold">Имя</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" id="name" placeholder="Иван" name="name" value="{{ $student->name }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="patronymic" class="col-lg-2 col-form-label font-weight-bold">Отчество</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" id="patronymic" placeholder="Иванович" name="patronymic" value="{{ $student->patronymic }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="surname" class="col-lg-2 col-form-label font-weight-bold">Фамилия</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" id="surname" placeholder="Иванов" name="surname" value="{{ $student->surname }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="student_ticket" class="col-lg-2 font-weight-bold">Студенческий билет</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" id="student_ticket" placeholder="Иванов" name="student_ticket" value="{{ $student->student_ticket }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="rights" class="col-lg-2 font-weight-bold">Права</label>
                <div class="col-lg-10">
                    <select class="custom-select" id="rights" name="rights">
                        <option value="1" {{ $student->user()->value("rights_id") == 1 ? 'selected' : '' }}>Пользователь</option>
                        <option value="2" {{ $student->user()->value("rights_id") == 2 ? 'selected' : '' }}>Администратор</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn">Изменить</button>
        </form>
    </div>
    <script src="{{ asset('js/admin/students/student-update.js') }}"></script>
    <script src="{{ asset('js/admin/students/student-active-submenu.js') }}"></script>
    <script src="{{ asset('js/admin/students/student-update-password-correction.js') }}"></script>
@endsection

