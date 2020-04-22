@extends('base.base')

@section('content')
    <link href="{{ asset('css/student-profile.css') }}" rel="stylesheet">

    @if (session('result'))
        @if (session('result')['status'] == 'failure')
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('result')['message'] }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @elseif (session('result')['status'] == 'success')
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('result')['message'] }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    @endif
    <div id="confirm-cancel-application" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Подтверждение отмены заявки</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Вы действительно хотите отменить заявку?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="button confirm-cancel-application-button" data-dismiss="modal">Да</button>
                    <button type="button" class="button" data-dismiss="modal">Отмена</button>
                </div>
            </div>
        </div>
    </div>
    <div id="is-cancel-confirmed" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Успех</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="message">Заявка успешно отменена</p>
                </div>
            </div>
        </div>
    </div>
    <div id="is-cancel-failure" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Неудача</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="message">Не удалось отменить заявку</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row no-gutters">
        <div class="col-12 text-dark">
            <div class="bg-white p-3 rounded drop-shadow">
                <div class="profile-title">Профиль студента</div>
                <hr>
                <div class="bg-white mr-lg-3 p-3 rounded">
                    <div class="profile-name">{{ $student->getFullName() }}</div>
                    <div class="right">
                        @switch($student->user()->first()->rights_id)
                            @case(1)
                                Пользователь
                            @break

                            @case(2)
                                Администратор
                            @break
                        @endswitch
                    </div>
                </div>
                <ul class="nav nav-tabs" id="tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active text-dark" id="home-tab" data-toggle="tab" href="#current-request" role="tab" aria-controls="home" aria-selected="true">Текущая заявка</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" id="profile-tab" data-toggle="tab" href="#request-history" role="tab" aria-controls="profile" aria-selected="false">История заявок</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" id="profile-tab" data-toggle="tab" href="#student-edit" role="tab" aria-controls="profile" aria-selected="false">Редактирование</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="current-request" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="request-container mt-2">
                            @if (!$currentApplications->isEmpty())
                                @foreach($currentApplications as $currentApplication)
                                    <div class="request mt-2  @switch($currentApplication->status_id)
                                    @case(1)
                                            wait
                                    @break

                                    @case(2)
                                            success
                                    @break

                                    @case(3)
                                            failure
                                    @break
                                    @endswitch">
                                        <div class="request-inner d-flex">
                                            <div class="p-2">
                                                <div class="font-weight-bolder">Преподаватель:</div>
                                                <div class="teacher-name">{{ $currentApplication->teacher()->first()->getFullName() }}</div>
                                            </div>
                                            <div class="p-2">
                                                <div class="font-weight-bolder">Деятельность:</div>
                                                <div class="teacher-name">
                                                    @if ($currentApplication->type_id == 1)
                                                        Практика
                                                    @elseif ($currentApplication->type_id == 2)
                                                        Диплом
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="p-2">
                                                <div class="font-weight-bolder">Статус</div>
                                                <div>
                                                    @switch($currentApplication->status_id)
                                                        @case(1)
                                                        Ожидание
                                                        @break

                                                        @case(2)
                                                        Принята
                                                        @break

                                                        @case(3)
                                                        Отклонена
                                                        @break
                                                    @endswitch
                                                </div>
                                            </div>
                                            <div class="p-2">
                                                <div class="font-weight-bolder">Дата отправки</div>
                                                <div class="date">{{ $currentApplication->created_at }}</div>
                                            </div>
                                            @if ($currentApplication->reply_datetime)
                                                <div class="p-2">
                                                    <div class="font-weight-bolder">Дата ответа</div>
                                                    <div class="date">{{ $currentApplication->reply_datetime }}</div>
                                                </div>
                                            @endif
                                        </div>
                                        <button class="button ml-2 application-cancel-button"
                                                teacher-id="{{ $currentApplication->teacher()->value('id') }}"
                                                type-id="{{ $currentApplication->type_id }}" data-toggle="modal"
                                                data-target="#confirm-cancel-application">
                                            Отменить заявку
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="pt-3">У вас нет заявок</div>
                            @endif
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
                    <div class="tab-pane fade" id="student-edit" role="tabpanel" aria-labelledby="home-tab">

                        <div class="p-2 mt-3 d-flex justify-content-between align-items-center change text-white">
                            <div>Изменить логин</div>
                            <i class="fas fa-arrow-up toggle-login-form"></i>
                        </div>
                        <div class="form-container p-3" id="login-update">
                            <form action="{{ route('student_login_update', ['student' => $student->id]) }}" method="POST" id="login-update-form" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label for="login">Логин</label>
                                    <input type="text" class="form-control"
                                           user-id="{{ $student->user()->value('id') }}" id="login" name="login"
                                           value="{{ $student->user()->value('login') }}">
                                </div>
                                <div class="form-group">
                                    <label for="password">Пароль</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                                <button type="submit" class="button button-large">Изменить</button>
                            </form>
                        </div>
                        <div class="p-2 mt-3 d-flex justify-content-between align-items-center change text-white">
                            <div>Изменить пароль</div>
                            <i class="fas fa-arrow-down toggle-password-form"></i>
                        </div>
                        <div class="form-container p-3" id="password-update">
                            <form action="{{ route('student_password_update', ['student' => $student->id]) }}" class="mt-3" method="POST" id="password-update-form" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label for="old_password">Старый пароль</label>
                                    <input type="password" class="form-control" id="old_password" name="old_password">
                                </div>
                                <div class="form-group">
                                    <label for="new_password">Новый пароль</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password">
                                </div>
                                <div class="form-group">
                                    <label for="new_password_confirmation">Повторить пароль</label>
                                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation">
                                </div>
                                <button type="submit" class="button button-large">Изменить</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/student-profile.js') }}"></script>
    <script src="{{ asset('js/cancel-application.js') }}"></script>
@endsection
