@extends('admin.base.base')

@section('content')
    <link href="{{ asset('css/admin/groups/group-update.css') }}" rel="stylesheet">
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('status') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="content">
        <h3>Обновление группы</h3>
{{--        @if ($errors->any())--}}
{{--            <div class="alert alert-danger">--}}
{{--                @foreach($errors->all() as $error)--}}
{{--                    {{ $error }}--}}
{{--                    <br>--}}
{{--                @endforeach--}}
{{--            </div>--}}
{{--        @endif--}}
        <form action="{{ route('groups.update', ['group' => $group->id]) }}" method="POST" enctype="multipart/form-data" id="groupUpdate">
            @csrf
            <input type="hidden" name="_method" value="PUT">

            <div class="form-group">
                <label for="name">Название</label>
                <input type="text" class="form-control" id="name" name="name" group-id="{{ $group->id }}" value="{{ $group->name }}">
            </div>
            <div class="form-group">
                <label class="disabled-text">Год</label>
                <input type="text" class="form-control disabled-text" value="{{ $group->year }}" disabled>
            </div>
            @if ($countGroupStoryForGroup == 1)
                <div class="form-group">
                    <label for="direction">Направление</label>
                    <select class="custom-select" id="direction" name="direction">
                        @foreach($directions as $direction)
                            <option value="{{ $direction->id }}" {{ $group->direction()->first()->id == $direction->id ? 'selected' : '' }}>{{ $direction->direction }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="course">Курс</label>
                    <select class="custom-select" id="course" name="course">
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ $group->course()->first()->id == $course->id ? 'selected' : '' }}>{{ $course->course }}</option>
                        @endforeach
                    </select>
                </div>
            @else
                <div class="form-group">
                    <label class="disabled-text">Направление</label>
                    <select class="custom-select" class="disabled-text" disabled>
                        @foreach($directions as $direction)
                            <option class="disabled-text" value="{{ $direction->id }}" {{ $group->direction()->first()->id == $direction->id ? 'selected' : '' }}>{{ $direction->direction }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="disabled-text">Курс</label>
                    <select class="custom-select" class="disabled-text" disabled>
                        @foreach($courses as $course)
                            <option class="disabled-text" value="{{ $course->id }}" {{ old('course') == $course->id ? 'selected' : '' }}>{{ $course->course }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="form-group">
                <label for="students">Актуальный список групп студентов</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="students" name="students" lang="ru">
                    <label class="custom-file-label" for="students" data-browse="Загрузите csv с актуальным списком студентов группы">Выберите файл</label>
                </div>
            </div>
            <button type="submit" class="btn">Изменить</button>
        </form>
    </div>

    <script src="{{ asset('js/admin/groups/group-active-submenu.js') }}"></script>
    <script src="{{ asset('js/admin/groups/group-update.js') }}"></script>
    <script src="{{ asset('js/additional-methods.js') }}"></script>
@endsection
