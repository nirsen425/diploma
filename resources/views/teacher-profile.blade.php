@extends('base.base')

@section('content')
    <link href="{{ asset('css/teacher-profile.css') }}" rel="stylesheet">
    <div class="row no-gutters">
        <div class="col-lg-4 text-center mb-3 mb-lg-0 text-dark">
            <div class="bg-white mr-lg-3 p-3 rounded drop-shadow">
                <div class="vocation">Преподаватель</div>
                <hr>
                <div class="photo-container">
{{--                    <img src="{{ asset("storage/images/" . $teacher->photo) }}" class="img-fluid rounded-circle profile-photo" alt="Преподаватель">--}}
                    <img src="http://images6.fanpop.com/image/photos/35100000/Emma-Watson-Icons-emma-watson-35139263-200-200.jpg" class="img-fluid rounded-circle profile-photo" alt="Преподаватель">
                </div>
                <div class="profile-name">Имя Отчество Фамилия</div>
                <div class="right">Пользователь</div>
            </div>
        </div>
        <div class="col-lg-8 text-dark">
            <div class="bg-white p-3 rounded drop-shadow">
                <div class="profile-title">Профиль</div>
                <hr>
                <ul class="nav nav-tabs" id="tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active text-dark" id="home-tab" data-toggle="tab" href="#teacher-edit" role="tab" aria-controls="home" aria-selected="true">Редактирование</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" id="profile-tab" data-toggle="tab" href="#practice-student" role="tab" aria-controls="profile" aria-selected="false">Ваши практиканты</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" id="profile-tab" data-toggle="tab" href="#diploma-student" role="tab" aria-controls="profile" aria-selected="false">Сдающие диплом</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" id="contact-tab" data-toggle="tab" href="#new-request" role="tab" aria-controls="contact" aria-selected="false">Новые заявки</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="teacher-edit" role="tabpanel" aria-labelledby="home-tab">
                        <form action="{{ route('teacher_register') }}" class="mt-3" method="POST" id="teacherRegistration" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="login">Логин</label>
                                <input type="text" class="form-control" id="login" name="login" value="{{ old('login') }}">
                            </div>
                            <div class="form-group">
                                <label for="old_password">Старый пароль</label>
                                <input type="old_password" class="form-control" id="old_password" name="password">
                            </div>
                            <div class="form-group">
                                <label for="new_password">Новый пароль</label>
                                <input type="new_password" class="form-control" id="new_password" name="new_password">
                            </div>
                            <div class="form-group">
                                <label for="photo">Фотография</label>
                                <div class="custom-file">
                                    <input type="file" accept="image/*" class="custom-file-input" id="photo" name="photo" lang="ru">
                                    <label class="custom-file-label" for="photo" data-browse="Изменить фото">Выберите фото</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="short_description">Ваше краткое описание</label>
                                <textarea class="form-control" id="short_description" name="short_description" rows="3">{{ old('short_description') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="full_description">Ваше полное описание</label>
                                <textarea class="form-control" id="full_description" name="full_description">{{ old('full_description') }}</textarea>
                            </div>
                            <input type="hidden" name="photo_x" id="photo_x">
                            <input type="hidden" name="photo_y" id="photo_y">
                            <input type="hidden" name="photo_width" id="photo_width">
                            <input type="hidden" name="photo_height" id="photo_height">
                            <button type="submit" class="button button-large">Изменить</button>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="practice-student" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="student-container">
                            <div class="student font-weight-bolder">
                                <div class="student-name">Алексей Конюхов</div>
                                <button class="button">Отклонить</button>
                            </div>
                            <div class="student font-weight-bolder">
                                <div class="student-name">Алексей Конюхов</div>
                                <button class="button">Отклонить</button>
                            </div>
                            <div class="student font-weight-bolder">
                                <div class="student-name">Алексей Конюхов</div>
                                <button class="button">Отклонить</button>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="diploma-student" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="student-container">
                            <div class="student font-weight-bolder">
                                <div class="student-name">Алексей Конюхов</div>
                                <button class="button">Отклонить</button>
                            </div>
                            <div class="student font-weight-bolder">
                                <div class="student-name">Алексей Конюхов</div>
                                <button class="button">Отклонить</button>
                            </div>
                            <div class="student font-weight-bolder">
                                <div class="student-name">Алексей Конюхов</div>
                                <button class="button">Отклонить</button>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="new-request" role="tabpanel" aria-labelledby="contact-tab">
                        <div class="request-container">
                            <div class="request font-weight-bolder">
                                <div class="request-name">Алексей Конюхов</div>
                                <button class="button">Принять</button>
                            </div>
                            <div class="request font-weight-bolder">
                                <div class="request-name">Алексей Конюхов</div>
                                <button class="button">Принять</button>
                            </div>
                            <div class="request font-weight-bolder">
                                <div class="request-name">Алексей Конюхов</div>
                                <button class="button">Принять</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
