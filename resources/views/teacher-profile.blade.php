@extends('base.base')

@section('content')
    <link href="{{ asset('css/teacher-profile.css') }}" rel="stylesheet" xmlns="http://www.w3.org/1999/html">

{{--    Модальные окна для одобрения заявки--}}
    <div id="confirm-approve-application" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Подтверждение одобрения заявки</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Вы действительно хотите одобрить заявку?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="button confirm-approve-application-button" data-dismiss="modal">Да</button>
                    <button type="button" class="button" data-dismiss="modal">Отмена</button>
                </div>
            </div>
        </div>
    </div>
    <div id="is-approve-confirmed" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Успех</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="message">Заявка успешно одобрена</p>
                </div>
            </div>
        </div>
    </div>
    <div id="is-approve-failure" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Неудача</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="message">Не удалось одобрить заявку</p>
                </div>
            </div>
        </div>
    </div>

    {{--    Модальные окна для отклонения заявки--}}
    <div id="confirm-reject-application" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Подтверждение отклонения заявки</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Вы действительно хотите отклонить заявку?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="button confirm-reject-application-button" data-dismiss="modal">Да</button>
                    <button type="button" class="button" data-dismiss="modal">Отмена</button>
                </div>
            </div>
        </div>
    </div>
    <div id="is-reject-confirmed" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Успех</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="message">Заявка успешно отклонена</p>
                </div>
            </div>
        </div>
    </div>
    <div id="is-reject-failure" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Неудача</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="message">Не удалось отклонить заявку</p>
                </div>
            </div>
        </div>
    </div>

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
    @if (session('notify_failure'))
        <div class="alert alert-warning alert-dismissible fade show">
            {{ session('notify_failure') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="row no-gutters">
        <div class="col-lg-4 text-center mb-3 mb-lg-0 text-dark">
            <div class="bg-white mr-lg-3 p-3 rounded drop-shadow">
                <div class="vocation">Преподаватель</div>
                <hr>
                <div class="photo-container">
                    <img src="{{ asset("storage/images/" . $teacher->photo) }}"
                         id="profile-photo"
                         class="img-fluid rounded-circle profile-photo" alt="Преподаватель">
                </div>
                <a type="button" class="button mt-2" id="deletePhoto">Удалить фото</a>
                <div class="profile-name">{{ $teacher->getFullName() }}</div>
                <div class="right">
                    @switch($teacher->user()->first()->rights_id)
                        @case(1)
                        Пользователь
                        @break

                        @case(2)
                        Администратор
                        @break
                    @endswitch
                </div>
            </div>
        </div>
        <div class="col-lg-8 text-dark">
            <div class="bg-white p-3 rounded drop-shadow">
                <div class="profile-title">Профиль</div>
                <hr>
                <ul class="nav nav-tabs" id="tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active text-dark" id="home-tab" data-toggle="tab" href="#new-request" role="tab" aria-controls="home" aria-selected="true">Новые заявки</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" id="profile-tab" data-toggle="tab" href="#practice-student" role="tab" aria-controls="profile" aria-selected="false">Ваши практиканты</a>
                    </li>
{{--                    <li class="nav-item">--}}
{{--                        <a class="nav-link text-dark" id="profile-tab" data-toggle="tab" href="#diploma-student" role="tab" aria-controls="profile" aria-selected="false">Сдающие диплом</a>--}}
{{--                    </li>--}}
                    <li class="nav-item">
                        <a class="nav-link text-dark" id="contact-tab" data-toggle="tab" href="#practice-info" role="tab" aria-controls="profile" aria-selected="false">Информация</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" id="contact-tab" data-toggle="tab" href="#teacher-files" role="tab" aria-controls="profile" aria-selected="false">Файлы</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" id="contact-tab" data-toggle="tab" href="#teacher-edit" role="tab" aria-controls="contact" aria-selected="false">Редактирование</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="new-request" role="tabpanel" aria-labelledby="contact-tab">
                        <div class="request-container">
                            @if (!$teacherWaitApplications->isEmpty())
                                @foreach($teacherWaitApplications as $teacherWaitApplication)
                                    <div class="request font-weight-bold">
                                        <div class="request-name">{{ $teacherWaitApplication->student()->first()->getFullName() }}</div>
                                        <div class="request-group-name mb-2">{{ $teacherWaitApplication->student()->first()->group()->first()->name }}</div>
                                        <div class="request-email mb-2">{{ $teacherWaitApplication->student()->first()->user()->first()->email }}</div>
                                        <button class="button application-approve-button"
                                                student-id="{{ $teacherWaitApplication->student()->value('id') }}"
                                                type-id="{{ $teacherWaitApplication->type_id }}" data-toggle="modal"
                                                data-target="#confirm-approve-application">
                                            Принять
                                        </button>
                                        <button class="button application-reject-button"
                                                student-id="{{ $teacherWaitApplication->student()->value('id') }}"
                                                type-id="{{ $teacherWaitApplication->type_id }}" data-toggle="modal"
                                                data-target="#confirm-reject-application">
                                            Отклонить
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="pt-3 no-request">У вас нет заявок</div>
                            @endif
                        </div>
                    </div>

                    <div class="tab-pane fade" id="practice-student" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="request-container">
                            @if (!empty($confirmPracticeApplicationStudents))
                                @foreach($confirmPracticeApplicationStudents as $confirmPracticeApplicationStudent)
                                    <div class="request font-weight-bolder">
                                        <div class="request-name">{{ $confirmPracticeApplicationStudent->getFullName() }}</div>
                                        <div class="request-group-name mb-2">{{ $confirmPracticeApplicationStudent->group()->first()->name }}</div>
                                        <div class="request-email mb-2">{{ $confirmPracticeApplicationStudent->user()->first()->email }}</div>
                                        <button class="button application-reject-button"
                                                student-id="{{ $confirmPracticeApplicationStudent->id }}"
                                                type-id="1" data-toggle="modal"
                                                data-target="#confirm-reject-application">
                                            Отклонить
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="pt-3 no-request">У вас нет практикантов</div>
                            @endif
                        </div>
                        @php
                            $placesMessage = trans_choice('messages.places', $teacher->countFreePracticePlaces());
                            $countPlaces = explode(' ', $placesMessage)[0];
                            // Правильная форма слова(место|места|мест)
                            $messageNumberForm = explode(' ', $placesMessage)[1];
                            $teacherLimits = $teacher->currentYearPracticeLimits();
                        @endphp
                        <div class="p-2" id="count-practice-places">На практику {{ $currentYear }} у вас осталось <span
                                    id="free-practice-places">{{ $countPlaces }}</span> <span
                                    id="places-number-form">{{ $messageNumberForm }}</span>
                            из {{ $teacherLimits ? $teacherLimits: 0 }}</div>
                    </div>

{{--                    <div class="tab-pane fade" id="diploma-student" role="tabpanel" aria-labelledby="profile-tab">--}}
{{--                        <div class="request-container">--}}
{{--                            @if (!empty($confirmDiplomaApplicationStudents))--}}
{{--                                @foreach($confirmDiplomaApplicationStudents as $confirmDiplomaApplicationStudent)--}}
{{--                                    <div class="request font-weight-bolder">--}}
{{--                                        <div class="request-name">{{ $confirmDiplomaApplicationStudent->getFullName() }}</div>--}}
{{--                                        <button class="button application-reject-button"--}}
{{--                                                student-id="{{ $confirmDiplomaApplicationStudent->id }}"--}}
{{--                                                type-id="2" countable="yes" data-toggle="modal"--}}
{{--                                                data-target="#confirm-reject-application">--}}
{{--                                            Отклонить--}}
{{--                                        </button>--}}
{{--                                    </div>--}}
{{--                                @endforeach--}}
{{--                            @else--}}
{{--                                <div class="pt-3 no-request">У вас нет сдающих диплом</div>--}}
{{--                            @endif--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="tab-pane fade" id="practice-info" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="d-flex pl-3 pt-3">
                            <div class="d-flex flex-column">
                                <label for="direction-practice" class="font-weight-bold direction">Направление</label>
                                <select class="mb-2 text-center" name="direction-practice">
                                    @foreach($directions as $direction)
                                        <option value="{{ $direction->id }}" {{ $direction->id == $selectedDirectionIdPractice ? "selected" : "" }}> {{ $direction->direction . ' ' . $direction->direction_name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-flex flex-column ml-2">
                                <label for="course-practice" class="font-weight-bold course">Курс</label>
                                <select class="mb-2" name="course-practice">
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ $course->id == $selectedCourseIdPractice ? "selected" : "" }}>  {{ $course->course }}  </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row pl-3 pt-3 pr-3">
                            <label class="col-lg-3 col-form-label font-weight-bold application-time">Сроки подачи заявок:</label>
                            <label class="col-lg-9 col-form-label application-time">
                                c <span id="application-start">  {{ $practice->application_start }} </span>
                                по <span id="application-end">  {{ $practice->application_end }} </span>
                            </label>
                        </div>
                        <div class="form-group row pl-3 pt-3 pr-3">
                            <label class="col-lg-3 col-form-label font-weight-bold practice-time">Сроки практики:</label>
                            <label class="col-lg-9 col-form-label practice-time">
                                с <span id="practice-start"> {{ $practice->practice_start }} </span>
                                по <span id="practice-end"> {{ $practice->practice_end }} </span>
                            </label>
                        </div>
                        <div class="form-group row pl-3 pt-3 pr-3">
                            <label class="col-lg-3 col-form-label font-weight-bold practice-info"> Информация: </label>
                            <label class="col-lg-9 col-form-label practice-info">
                                <span id="practice-info-text"> {!! $practice->practice_info !!} </span>
                            </label>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="teacher-files" role="tabpanel" aria-labelledby="home-tab">
                        <div class="d-flex pl-3 pt-3">
                            <div class="d-flex flex-column">
                                <label for="direction-files" class="font-weight-bold direction">Направление</label>
                                <select class="mb-2 text-center" name="direction-files">
                                    @foreach($directions as $direction)
                                        <option value="{{ $direction->id }}" {{ $direction->id == $selectedDirectionIdFiles ? "selected" : "" }}> {{ $direction->direction . ' ' . $direction->direction_name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-flex flex-column ml-2">
                                <label for="course-files" class="font-weight-bold course">Курс</label>
                                <select class="mb-2" name="course-files">
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ $course->id == $selectedCourseIdFiles ? "selected" : "" }}>  {{ $course->course }}  </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if(!$files->isEmpty())
                            <div class="pt-3 pb-3" id="files-table">
                                <table class="table bg-light table-hover">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th class="d-none">id</th>
                                        <th scope="col" class="pl-8">Название файла</th>
                                        <th scope="col" class="pl-8">Дата загрузки</th>
                                        <th scope="col" class="pl-8">Действия</th>
                                    </tr>
                                    </thead>
                                    <tbody id="files-place">
                                    @foreach($files as $file)
                                        <tr>
                                            <td class="d-none file-id">{{ $file->id }}</td>
                                            <td class="align-bottom p-8">{{ $file->name }}.{{ $file->extension }}</td>
                                            <td class="align-bottom p-8">{{ $file->created_at }}</td>
                                            <td>
                                                <a href="{{ route('teacher_file_download', ['directionId' => $selectedDirectionIdFiles, 'courseId' => $selectedCourseIdFiles, 'fileId' => $file->id]) }}" class="download">
                                                    <i class="fa fa-download"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-3" id="not-files">Нет загруженных файлов</div>
                        @endif
                    </div>
                    <div class="tab-pane fade" id="teacher-edit" role="tabpanel" aria-labelledby="home-tab">

                        <div class="p-2 mt-3 d-flex justify-content-between align-items-center change text-white">
                            <div>Изменить логин</div>
                            <i class="fas fa-arrow-up toggle-login-form"></i>
                        </div>
                        <div class="form-container p-3" id="login-update">
                            <form action="{{ route('teacher_login_update', ['teacher' => $teacher->id]) }}" method="POST" id="login-update-form">
                                @csrf

                                <div class="form-group">
                                    <label for="login">Логин</label>
                                    <input type="text" class="form-control"
                                           user-id="{{ $teacher->user()->value('id') }}" id="login" name="login"
                                           value="{{ $teacher->user()->value('login') }}">
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
                            <i class="fas fa-arrow-up toggle-password-form"></i>
                        </div>
                        <div class="form-container p-3" id="password-update">
                            <form action="{{ route('teacher_password_update', ['teacher' => $teacher->id]) }}" class="mt-3" method="POST" id="password-update-form">
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

                        <div class="p-2 mt-3 d-flex justify-content-between align-items-center change text-white">
                            <div>Изменить email</div>
                            <i class="fas fa-arrow-up toggle-email-form"></i>
                        </div>
                        <div class="form-container p-3" id="email-update">
                            <form action="{{ route('user_email_update') }}" class="mt-3" method="POST" id="email-update-form">
                                @csrf

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="text" class="form-control"
                                           id="email" name="email"
                                           value="{{ $teacher->user()->value('email') }}">
                                </div>
                                <div class="form-group">
                                    <label for="password-for-email">Пароль</label>
                                    <input type="password" class="form-control" id="password-for-email" name="password">
                                </div>
                                <button type="submit" class="button button-large">Изменить</button>
                            </form>
                        </div>

                        <div class="p-2 mt-3 d-flex justify-content-between align-items-center change text-white">
                            <div>Изменить фотографию</div>
                            <i class="fas fa-arrow-up toggle-photo-form"></i>
                        </div>
                        <div class="form-container p-3" id="photo-update">
                            <form action="{{ route('teacher_photo_update', ['teacher' => $teacher->id]) }}" class="mt-3" method="POST" id="photo-update-form" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label for="photo">Фотография</label>
                                    <div class="custom-file">
                                        <input type="file" accept="image/*" class="custom-file-input" id="photo" name="photo" lang="ru" data-rule-required="true" data-msg-accept="Файл должен иметь один из форматов jpg, jpeg или png">
                                        <label class="custom-file-label" for="photo" data-browse="Изменить фото">Выберите фото</label>
                                    </div>
                                    <div class="teacher-image-container mt-3">
                                        <img id="teacherImage"/>
                                    </div>
                                </div>

                                <input type="hidden" name="photo_x" id="photo_x">
                                <input type="hidden" name="photo_y" id="photo_y">
                                <input type="hidden" name="photo_width" id="photo_width">
                                <input type="hidden" name="photo_height" id="photo_height">

                                <button type="submit" class="button button-large">Изменить</button>
                            </form>
                        </div>

                        <div class="p-2 mt-3 d-flex justify-content-between align-items-center change text-white">
                            <div>Изменить краткое описание</div>
                            <i class="fas fa-arrow-up toggle-short-description-form"></i>
                        </div>
                        <div class="form-container p-3" id="short-description-update">
                            <form action="{{ route('teacher_short_description_update', ['teacher' => $teacher->id]) }}" class="mt-3" method="POST" id="short-description-update-form">
                                @csrf

                                <div class="form-group">
                                    <label for="teacher_short_description_update">Ваше краткое описание</label>
                                    <textarea class="form-control" id="short_description" name="short_description" rows="3">{{ $teacher->short_description }}</textarea>
                                </div>
                                <button type="submit" class="button button-large">Изменить</button>
                            </form>
                        </div>

                        <div class="p-2 mt-3 d-flex justify-content-between align-items-center change text-white">
                            <div>Изменить полное описание</div>
                            <i class="fas fa-arrow-up toggle-full-description-form"></i>
                        </div>
                        <div class="form-container p-3" id="full-description-update">
                            <form action="{{ route('teacher_full_description_update', ['teacher' => $teacher->id]) }}" class="mt-3" method="POST" id="full-description-update-form" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label for="full_description">Ваше полное описание</label>
                                    <textarea class="form-control" id="full_description" name="full_description">{{ $teacher->full_description }}</textarea>
                                </div>
                                <button type="submit" class="button button-large">Изменить</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('js/local-time.js') }}"></script>
    <script src="{{ asset('js/teacher-profile.js') }}"></script>
    <script src="{{ asset('js/confirm-application.js') }}"></script>
    <script src="{{ asset('js/reject-application.js') }}"></script>
    <script src="{{ asset('js/additional-methods.js') }}"></script>
@endsection
