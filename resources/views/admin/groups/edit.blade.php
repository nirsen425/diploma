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
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    {{ $error }}
                    <br>
                @endforeach
            </div>
        @endif
        <form action="{{ route('groups.update', ['group' => $group->id]) }}" method="POST" enctype="multipart/form-data" id="groupUpdate">
            @csrf
            <input type="hidden" name="_method" value="PUT">

            <div class="form-group">
                <label for="direction">Направление</label>
                <select class="custom-select" id="direction" name="direction">
                    @foreach($directions as $direction)
                        <option value="{{ $direction->id }}" {{ $group->direction()->first()->id == $direction->id ? 'selected' : '' }}>{{ $direction->direction }}</option>
                    @endforeach
                </select>
            </div>
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
