@extends('base.base')

@section('content')
    <link href="{{ asset('css/student-profile.css') }}" rel="stylesheet">
    <div class="row no-gutters">

        <div class="col-12 text-dark">
            <div class="bg-white p-3 rounded drop-shadow">
                <div class="profile-title">Профиль</div>
                <hr>
                <div class="bg-white mr-lg-3 p-3 rounded">
                    <div class="vocation">Студент</div>
                    <div class="profile-name">Имя Отчество Фамилия</div>
                    <div class="right">Пользователь</div>
                </div>
                <ul class="nav nav-tabs" id="tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active text-dark" id="home-tab" data-toggle="tab" href="#student-edit" role="tab" aria-controls="home" aria-selected="true">Редактирование</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" id="profile-tab" data-toggle="tab" href="#current-request" role="tab" aria-controls="profile" aria-selected="false">Текущая заявка</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" id="profile-tab" data-toggle="tab" href="#request-history" role="tab" aria-controls="profile" aria-selected="false">История заявок</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="student-edit" role="tabpanel" aria-labelledby="home-tab">

                        <div class="p-2 mt-3 d-flex justify-content-between align-items-center change text-white">
                            <div>Изменить логин</div>
                            <i class="fas fa-arrow-up toggle-login-form"></i>
                        </div>
                        <div class="form-container p-3" id="login-update">
                            <form action="{{ route('teacher_register') }}" method="POST" id="login-update-form" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label for="login">Логин</label>
                                    <input type="text" class="form-control" id="login" name="login" value="{{ old('login') }}">
                                </div>
                                <div class="form-group">
                                    <label for="old_password">Пароль</label>
                                    <input type="old_password" class="form-control" id="old_password" name="password">
                                </div>
                                <button type="submit" class="button button-large">Изменить</button>
                            </form>
                        </div>
                        <div class="p-2 mt-3 d-flex justify-content-between align-items-center change text-white">
                            <div>Изменить пароль</div>
                            <i class="fas fa-arrow-down toggle-password-form"></i>
                        </div>
                        <div class="form-container p-3" id="password-update">
                            <form action="{{ route('teacher_register') }}" class="mt-3" method="POST" id="password-update-form" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label for="old_password">Старый пароль</label>
                                    <input type="old_password" class="form-control" id="old_password" name="password">
                                </div>
                                <div class="form-group">
                                    <label for="new_password">Новый пароль</label>
                                    <input type="new_password" class="form-control" id="new_password" name="new_password">
                                </div>
                                <div class="form-group">
                                    <label for="new_password">Повторить пароль</label>
                                    <input type="confirm_password" class="form-control" id="confirm_password" name="confirm_password">
                                </div>
                                <button type="submit" class="button button-large">Изменить</button>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="current-request" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="request-container mt-2">
                            <div class="request d-flex mt-2 wait">
                                <div class="p-2">
                                    <div class="font-weight-bolder">Преподаватель:</div>
                                    <div class="teacher-name">Маянц Майя Львовна</div>
                                </div>
                                <div class="p-2">
                                    <div class="font-weight-bolder">Деятельность:</div>
                                    <div class="teacher-name">Практика</div>
                                </div>
                                <div class="p-2">
                                    <div class="font-weight-bolder">Статус</div>
                                    <div>Ожидание</div>
                                </div>
                                <div class="d-flex mt-2 mr-2 p-2 flex-grow-1 justify-content-end">
                                    <i class="far fa-clock"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="request-history" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="request-container mt-2">
                            <div class="request d-flex mt-2 success">
                                <div class="p-2">
                                    <div class="font-weight-bolder">Преподаватель:</div>
                                    <div class="teacher-name">Маянц Майя Львовна</div>
                                </div>
                                <div class="p-2">
                                    <div class="font-weight-bolder">Деятельность:</div>
                                    <div class="teacher-name">Практика</div>
                                </div>
                                <div class="p-2">
                                    <div class="font-weight-bolder">Статус</div>
                                    <div>Принята</div>
                                </div>
                            </div>
                            <div class="request d-flex mt-2 failure">
                                <div class="p-2">
                                    <div class="font-weight-bolder">Преподаватель:</div>
                                    <div class="teacher-name">Маянц Майя Львовна</div>
                                </div>
                                <div class="p-2">
                                    <div class="font-weight-bolder">Деятельность:</div>
                                    <div class="teacher-name">Практика</div>
                                </div>
                                <div class="p-2">
                                    <div class="font-weight-bolder">Статус</div>
                                    <div>Отклонена</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/student-profile.js') }}"></script>
@endsection
