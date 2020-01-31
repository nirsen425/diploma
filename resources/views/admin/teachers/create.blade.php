@extends('admin.base.base')

@section('content')
    <link href="{{ asset('css/admin/teachers/teacher-create.css') }}" rel="stylesheet">
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div class="content">
        <h3>Регистрация преподавателя</h3>
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    {{ $error }}
                    <br>
                @endforeach
            </div>
        @endif
        <form action="{{ route('teacher_register') }}" method="POST" id="teacherRegistration" enctype="multipart/form-data">
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
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="show" name="show" value="1" {{ old('show') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="show">Показывать преподавателя на сайте</label>
                </div>
            </div>
            <div class="form-group">
                <label for="rights">Права</label>
                <select class="custom-select" id="rights" name="rights">
                    <option value="1" {{ old('rights') == 1 ? 'selected' : '' }}>Пользователь</option>
                    <option value="2" {{ old('rights') == 2 ? 'selected' : '' }}>Администратор</option>
                </select>
            </div>
            <div class="form-group">
                <div class="teacher-image-container mt-3">
                    <img id="teacherImage"/>
                </div>
                <label for="photo">Фотография</label>
                <input type="file" accept="image/*" class="form-control-file" id="photo" name="photo">
            </div>
            <div class="form-group">
                <label for="short_description">Краткое описание преподавателя</label>
                <textarea class="form-control" id="short_description" name="short_description" rows="3">{{ old('short_description') }}</textarea>
            </div>
            <div class="form-group">
                <label for="full_description">Полное описание преподавателя</label>
                <textarea class="form-control" id="full_description" name="full_description">{{ old('full_description') }}</textarea>
            </div>
            <input type="hidden" name="photo_x" id="photo_x">
            <input type="hidden" name="photo_y" id="photo_y">
            <input type="hidden" name="photo_width" id="photo_width">
            <input type="hidden" name="photo_height" id="photo_height">
            <button type="submit" class="btn btn-danger">Зарегистрировать</button>
        </form>
    </div>
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('js/admin/teachers/teacher-create.js') }}"></script>
@endsection