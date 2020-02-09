@extends('base.base')

@section('content')
    <link href="{{ asset('css/teacher-list.css') }}" rel="stylesheet">
    <div class="row">
        @foreach($teachers as $teacher)
        <div class="col col-md-6">
            <div class="d-flex teacher-cart bg-white mt-3 rounded text-break">
                <div class="p-2">
                    <img src="http://images6.fanpop.com/image/photos/35100000/Emma-Watson-Icons-emma-watson-35139263-200-200.jpg" class="teacher-photo rounded" alt="Преподаватель">
                </div>
                <div class="p-2 d-flex flex-column">
                    <div class="teacher-name font-weight-bold">{{ $teacher->name . " " . $teacher->patronymic . " " . $teacher->surname }}</div>
                    <div class="teacher-short-description flex-grow-1">
                        {{ $teacher->short_description }}
                    </div>
                    <a href="{{ route('teacher_show', ['teacher' => $teacher->id]) }}" class="button align-self-end">Подробнее</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endsection

