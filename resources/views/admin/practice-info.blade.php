@extends('admin.base.base')

@section('content')
    <link href="{{ asset('css/admin/practice-info.css') }}" rel="stylesheet">
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('status') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="practice-info-container">
        <div class="container-fluid p-3">
            <div class="row">
                <h3 class="col-12 col-lg-6 mt-2">Информация и сроки</h3>
            </div>
            <div class="d-flex pt-3">
                <div class="d-flex flex-column">
                    <label for="direction" class="direction font-weight-bold year">Направление</label>
                    <select class="mb-2 text-center" name="direction">
                        @foreach($directions as $direction)
                            <option value="{{ $direction->id }}" {{ $direction->id == $selectedDirectionId ? "selected" : "" }}> {{ $direction->direction . ' ' . $direction->direction_name }} </option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex flex-column ml-2">
                    <label for="course" class="course font-weight-bold group">Курс</label>
                    <select class="mb-2 text-center" name="course">
                        @if(isset($courses))
                            @foreach($courses as $course)
                                @if(!isset($selectedCourseId))
                                    <option class="d-none" selected></option>
                                @endif
                                    <option value="{{ $course->id }}" {{ (isset($selectedCourseId) and $course->id == $selectedCourseId) ? "selected" : "" }}>  {{ $course->course }}  </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>
        @if(isset($selectedCourseId))

            <div class="container-fluid p-3">
                <form action="{{ route('practice_info_edit', ['directionId' => $selectedDirectionId, 'courseId' => $selectedCourseId]) }}" method="POST" id="practiceInfoEdit" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-bold application-time">Сроки подачи заявок:</label>
                    </div>
                    <div class="form-group row">
                        <label for="application_start" class="col-lg-2 col-form-label font-weight-bold">Начало:</label>
                        <div class="col-lg-10">
                            <input class="form-control" type="date" value="{{ $application_start }}" id="application_start" name="application_start" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="application_end" class="col-lg-2 col-form-label font-weight-bold">Конец:</label>
                        <div class="col-lg-10">
                            <input class="form-control" type="date" value="{{ $application_end }}" id="application_end" name="application_end" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-bold practice-time">Сроки практики:</label>
                    </div>
                    <div class="form-group row">
                        <label for="practice_start" class="col-lg-2 col-form-label font-weight-bold">Начало:</label>
                        <div class="col-lg-10">
                            <input class="form-control" type="date" value="{{ $practice_start }}" id="practice_start" name="practice_start" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="practice_end" class="col-lg-2 col-form-label font-weight-bold">Конец:</label>
                        <div class="col-lg-10">
                            <input class="form-control" type="date" value="{{ $practice_end }}" id="practice_end" name="practice_end" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="practice_info" class="col-lg-2 col-form-label text-break font-weight-bold practice-info">Информация:</label>
                        <div class="col-lg-10">
                            <textarea class="form-control" id="practice_info" name="practice_info"> {{ $practice_info }} </textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn">Изменить</button>
                </form>
            </div>

        @endif
    </div>

    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('js/admin/practice.js') }}"></script>
@endsection
