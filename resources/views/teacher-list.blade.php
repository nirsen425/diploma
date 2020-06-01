@extends('base.base')

@section('content')
    <link href="{{ asset('css/teacher-list.css') }}" rel="stylesheet">
    <div class="row">
        @if ($teachers)
            @foreach($teachers as $teacher)
            <div class="col col-md-6 mt-3">
                <div class="d-flex teacher-cart bg-white rounded text-break">
                    <div class="p-2">
                        <img src="{{ asset("storage/images/" . $teacher->photo) }}" class="teacher-photo rounded" alt="Преподаватель">
                    </div>
                    <div class="p-2 d-flex flex-column flex-grow-1">
                        <div class="teacher-name font-weight-bold">{{ $teacher->getFullName() }}</div>
                        <div class="teacher-short-description flex-grow-1">
                            {{ $teacher->short_description }}
                        </div>
                        <div class="teacher-limit">
                            На практику {{ $currentYear }} осталось {{ trans_choice('messages.places', $teacher->countFreePracticePlaces()) }} из {{ $teacher->currentYearPracticeLimits() }}
                        </div>
                        <a href="{{ route('teacher_show', ['teacher' => $teacher->id]) }}" class="button align-self-end mt-2">Подробнее</a>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <h4 class="col-12 text-center">В данный момент у вас нет преподавателей к которым можно записаться на практику {{ $currentYear }} года</h4>
        @endif
    </div>
@endsection

