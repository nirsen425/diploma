@extends('admin.base.base')

@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <h3>Регистрация преподавателя</h3>
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                {{ $error }}
                <br>
            @endforeach
        </div>
    @endif
    <form action="{{ route('teacher-register') }}" method="POST">
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
        <button type="submit" class="btn btn-danger">Зарегистрировать</button>
    </form>
@endsection