@extends('admin.base.base')

@section('content')
    <link href="{{ asset('css/admin/groups/group-create.css') }}" rel="stylesheet">
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('status') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="content shell">
        <h3>Создание группы</h3>
{{--        @if ($errors->any())--}}
{{--            <div class="alert alert-danger">--}}
{{--                @foreach($errors->all() as $error)--}}
{{--                    {{ $error }}--}}
{{--                    <br>--}}
{{--                @endforeach--}}
{{--            </div>--}}
{{--        @endif--}}
        <form action="{{ route('groups.store') }}" method="POST" enctype="multipart/form-data" id="groupCreate">
            @csrf

            <div class="form-group">
                <label for="name">Название</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
            </div>
            <div class="form-group">
                <label class="disabled-text">Год</label>
                <input type="text" class="form-control disabled-text" value="{{ $currentYear }}" disabled>
            </div>
            <div class="form-group">
                <label for="direction">Направление</label>
                <select class="custom-select" id="direction" name="direction">
                    @foreach($directions as $direction)
                        <option value="{{ $direction->id }}" {{ old('direction') == $direction->id ? 'selected' : '' }}>{{ $direction->direction }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="course">Курс</label>
                <select class="custom-select" id="course" name="course">
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course') == $course->id ? 'selected' : '' }}>{{ $course->course }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="students">Студенты группы</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="students" name="students" lang="ru">
                    <label class="custom-file-label" for="students" data-browse="Загрузите csv со студентами группы">Выберите файл</label>
                </div>
            </div>
            @if (isset($untranslatedGroup))
                <button type="submit" class="button button-large disabled">Создать</button>
                <div class="hint mt-1"><a href="{{ route('group_transfer') }}">Есть непереведенные группы</a></div>
            @else
                <button type="submit" class="button button-large">Создать</button>
            @endif
        </form>
    </div>

    <script src="{{ asset('js/admin/groups/group-create.js') }}"></script>
    <script src="{{ asset('js/additional-methods.js') }}"></script>
@endsection
