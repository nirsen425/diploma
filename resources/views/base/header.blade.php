@php
    use App\Page;

    $pages = Page::all();
@endphp
<header class="mb-3">
    @php
        $user = Auth::user();
    @endphp
    @if ($user)
        <div class="sub-menu  p-1 text-white">
            <div class="container text-right">
                @if ($user->user_type_id == 1 )
                    @if($user->rights_id == 2)
                        <a href="{{ route('admin') }}" class="mr-2">Панель администратора</a>
                    @endif
                    <a href="{{ route('student_cabinet_index') }}">Личный кабинет</a>
                @elseif ($user->user_type_id == 2)
                    @if($user->rights_id == 2)
                        <a href="{{ route('admin') }}" class="mr-2">Панель администратора</a>
                    @endif
                    <a href="{{ route('teacher_cabinet_index') }}">Личный кабинет</a>
                @endif
            </div>
        </div>
    @endif
    <nav class="navbar navbar-expand-sm navbar-dark">
        <div class="container">
            <a class="navbar-brand brand" href="/">ИТиАП</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarToggler">
                <ul class="navbar-nav mx-auto mt-lg-0">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/">Преподаватели</a>
                    </li>
                    @foreach($pages as $page)
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('page', ['slug' => $page->slug]) }}">{{ $page->title }}</a>
                        </li>
                    @endforeach
                </ul>
                @if($user)
                    <a href="{{ route('logout') }}" class="button"
                       onclick="event.preventDefault();
                       document.getElementById('logout-form').submit();">
                        Выход
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        {{ csrf_field() }}
                    </form>
                @else
                    <a href="{{ route('login') }}" class="button">Вход</a>
                @endif
            </div>
        </div>
    </nav>
{{--    <style>--}}
{{--        .nav-item > a {--}}
{{--            border-left: 1px solid rgba(255,255,255, 0.25);--}}
{{--            --}}
{{--        }--}}

{{--        .navbar-nav .nav-item:last-child {--}}
{{--            border-right: 1px solid #0e7079;--}}
{{--        }--}}

{{--        li {--}}
{{--            margin-right: 10px;--}}
{{--        }--}}

{{--        .navbar-nav  .nav-item {--}}
{{--            display: flex;--}}
{{--            align-items: center;--}}
{{--            height: 50px;--}}
{{--            border-left: 1px solid #0e7079;--}}
{{--        }--}}
{{--    </style>--}}
</header>
