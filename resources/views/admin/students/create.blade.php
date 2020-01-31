@extends('admin.base.base')

@section('content')
    <link href="{{ asset('css/admin/students/student-create.css') }}" rel="stylesheet">
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div class="content">
        <h3>Регистрация студента</h3>
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    {{ $error }}
                    <br>
                @endforeach
            </div>
        @endif
        <form action="{{ route('student_register') }}" method="POST" id="studentRegistration">
            @csrf

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="login">Логин</label>
                    <input type="text" class="form-control" id="login" name="login" value="{{ old('login') }}">
                </div>
                <div class="form-group col-md-6">
                    <label for="password">Временный пароль</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
            </div>
            <div class="form-group">
                <label for="name">Имя</label>
                <input type="text" class="form-control" id="name" placeholder="Иван" name="name" value="{{ old('name') }}">
            </div>
            <div class="form-group">
                <label for="patronymic">Отчество</label>
                <input type="text" class="form-control" id="patronymic" placeholder="Иванович" name="patronymic" value="{{ old('patronymic') }}">
            </div>
            <div class="form-group">
                <label for="surname">Фамилия</label>
                <input type="text" class="form-control" id="surname" placeholder="Иванов" name="surname" value="{{ old('surname') }}">
            </div>
            <div class="form-group">
                <label for="student_ticket">Студенческий билет</label>
                <input type="text" class="form-control" id="student_ticket" name="student_ticket" value="{{ old('student_ticket') }}">
            </div>
            <div class="form-group">
                <label for="rights">Права</label>
                <select class="custom-select" id="rights" name="rights">
                    <option value="1" {{ old('rights') == 1 ? 'selected' : '' }}>Пользователь</option>
                    <option value="2" {{ old('rights') == 2 ? 'selected' : '' }}>Администратор</option>
                </select>
            </div>
            <button type="submit" class="btn btn-danger">Зарегистрировать</button>
        </form>
    </div>
    <script src="{{ asset('js/admin/students/student-create.js') }}"></script>
@endsection
