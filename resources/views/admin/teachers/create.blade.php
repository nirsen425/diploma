@extends('admin.base.base')

@section('content')
    <link href="{{ asset('css/admin/teachers/teacher-create.css') }}" rel="stylesheet">
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div class="content shell">
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
                    <button type="button" class="button button-large mt-2" id="generateLogin">Сгенерировать случайный логин</button>
                </div>
                <div class="form-group col-md-6">
                    <label for="password">Пароль</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <button type="button" class="button button-large mt-2" id="insertPassword">Вставить стандартный пароль password</button>
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
                <label for="email">Email</label>
                <input type="text" class="form-control" id="email"
                       name="email" value="{{ old('email') }}">
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
                <div class="custom-file">
                    <input type="file" accept="image/*" class="custom-file-input" id="photo" name="photo" lang="ru" data-msg-accept="Файл должен иметь один из форматов jpg, jpeg или png">
                    <label class="custom-file-label" for="photo" data-browse="Изменить фото">Выберите фото</label>
                </div>
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
            <button type="submit" class="button button-large">Зарегистрировать</button>
        </form>
    </div>
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('js/admin/teachers/teacher-create.js') }}"></script>
    <script src="{{ asset('js/additional-methods.js') }}"></script>
@endsection