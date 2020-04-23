<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/admin/base/admin-base.js') }}"></script>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin/base/admin-base.css') }}" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand fixed-top bd-navbar">
        <button type="button" id="switchSidebar" class="btn text-white">
            <i class="fas fa-align-left"></i>
            <span>Переключить</span>
        </button>
        <div class="collapse navbar-collapse" id="navbarColor02">
            <ul class="navbar-nav">
                <li class="nav-item cursor-pointer">
                    <a class="nav-link" href="/"><i class="fas fa-external-link-alt"></i></a>
                </li>
            </ul>
        </div>
    </nav>

    <aside class="toggler">
        <div id="hideSidebar" class="d-block d-md-none">
            <i class="fas fa-arrow-left"></i>
        </div>
        <div class="sidebar-header">
            <h3>Админ-панель</h3>
        </div>

        <ul class="list-unstyled components">
            <p>Навигация</p>
            <li class="components-element">
                <a href="#teachersSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Преподаватели</a>
                <ul class="collapse list-unstyled" id="teachersSubmenu">
                    <li>
                        <a href="{{ route('lecturers.index') }}">Просмотреть</a>
                    </li>
{{--                    <li>--}}
{{--                        <a href="{{ route('teacher_register') }}">Добавить</a>--}}
{{--                    </li>--}}
                </ul>
            </li>
            <li class="components-element">
                <a href="#studentsSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Студенты</a>
                <ul class="collapse list-unstyled" id="studentsSubmenu">
                    <li>
                        <a href="{{ route('students.index') }}">Просмотреть</a>
                    </li>
{{--                    <li>--}}
{{--                        <a href="{{ route('student_register') }}">Добавить</a>--}}
{{--                    </li>--}}
                </ul>
            </li>
            <li class="components-element">
                <a href="#pagesSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Страницы</a>
                <ul class="collapse list-unstyled" id="pagesSubmenu">
                    <li>
                        <a href="{{ route('pages.index') }}">Просмотреть</a>
                    </li>
                    <li>
                        <a href="{{ route('pages.create') }}">Добавить</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route('teacher_applications') }}">Заявки</a>
            </li>
            <li>
                <a href="{{ route('teacher_limits', ['year' => date('Y')]) }}">Лимиты преподавателей</a>
            </li>
        </ul>
    </aside>

    <main class="toggler p-5">
        @yield('content')
    </main>

    <div id="overlay" class="toggler"></div>
</body>