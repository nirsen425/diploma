@extends('admin.base.base')

@section('content')
    <link href="{{ asset('css/admin/teachers/teacher-update.css') }}" rel="stylesheet">
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('status') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="content">
        <h3 class="mb-4">Редактирование преподавателя</h3>
{{--        @if ($errors->any())--}}
{{--            <div class="alert alert-danger">--}}
{{--                @foreach($errors->all() as $error)--}}
{{--                    {{ $error }}--}}
{{--                    <br>--}}
{{--                @endforeach--}}
{{--            </div>--}}
{{--        @endif--}}
        <form action="{{ route('lecturers.update', ['teacher' => $teacher->id]) }}" method="POST" id="teacherRegistration" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="PUT">

            <div class="form-group row ">
                <label for="login" class="col-lg-2 col-form-label font-weight-bold">Логин</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" id="login" name="login" user-id="{{ $teacher->user()->value('id') }}" value="{{ $teacher->user()->value('login') }}">
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
                    <input type="text" class="form-control" id="name" placeholder="Иван" name="name" value="{{ $teacher->name }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="patronymic" class="col-lg-2 col-form-label font-weight-bold">Отчество</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" id="patronymic" placeholder="Иванович" name="patronymic" value="{{ $teacher->patronymic }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="surname" class="col-lg-2 col-form-label font-weight-bold">Фамилия</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" id="surname" placeholder="Иванов" name="surname" value="{{ $teacher->surname }}">
                </div>
            </div>
{{--            <div class="form-group row">--}}
{{--                <div class="col-lg-2"></div>--}}
{{--                <div class="col-lg-10">--}}
{{--                    <div class="custom-control custom-switch">--}}
{{--                        <input type="checkbox" class="custom-control-input" id="show" name="show" value="1" {{ $teacher->show ? 'checked' : '' }}>--}}
{{--                        <label class="custom-control-label" for="show">Показывать преподавателя на сайте</label>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
            <div class="form-group row">
                <label for="rights" class="col-lg-2 font-weight-bold">Права</label>
                <div class="col-lg-10">
                    <select class="custom-select" id="rights" name="rights">
                        <option value="1" {{ $teacher->user()->value("rights_id") == 1 ? 'selected' : '' }}>Пользователь</option>
                        <option value="2" {{ $teacher->user()->value("rights_id") == 2 ? 'selected' : '' }}>Администратор</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="photo" class="col-lg-2 col-form-label font-weight-bold">Фотография</label>
                <div class="col-lg-10">
                    <div class="custom-file">
                        <input type="file" accept="image/*" class="custom-file-input" id="photo" name="photo" lang="ru">
                        <label class="custom-file-label" for="photo" data-browse="Изменить фото">Выберите фото</label>
                    </div>
                    <div class="teacher-image-container mt-3">
                        <img id="teacherImage" src="{{ asset("storage/images/" . $teacher->photo) }}"/>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="short_description" class="col-lg-2 col-form-label font-weight-bold">Краткое описание преподавателя</label>
                <div class="col-lg-10">
                    <textarea class="form-control" id="short_description" name="short_description" rows="3">{{ $teacher->short_description }}</textarea>
                </div>
            </div>
            <div class="form-group row">
                <label for="full_description" class="col-lg-2 col-form-label text-break font-weight-bold">Полное описание преподавателя</label>
                <div class="col-lg-10">
                    <textarea class="form-control" id="full_description" name="full_description">{{ $teacher->full_description }}</textarea>
                </div>
            </div>
            <input type="hidden" name="photo_x" id="photo_x">
            <input type="hidden" name="photo_y" id="photo_y">
            <input type="hidden" name="photo_width" id="photo_width">
            <input type="hidden" name="photo_height" id="photo_height">
            <button type="submit" class="btn">Изменить</button>
        </form>
    </div>
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('js/admin/teachers/teacher-update.js') }}"></script>
    <script src="{{ asset('js/admin/teachers/teacher-active-sumbenu.js') }}"></script>
    <script src="{{ asset('js/admin/teachers/teacher-update-password-correction.js') }}"></script>
@endsection
