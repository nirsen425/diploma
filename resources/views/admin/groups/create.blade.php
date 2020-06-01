@extends('admin.base.base')

@section('content')
    <link href="{{ asset('css/admin/groups/group-create.css') }}" rel="stylesheet">
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div class="content">
        <h3>Создание группы</h3>
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    {{ $error }}
                    <br>
                @endforeach
            </div>
        @endif
        <form action="{{ route('groups.store') }}" method="POST" id="groupRegistration">
            @csrf

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="name">Название</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                </div>
            </div>
            <div class="form-group">
                <label for="direction">Права</label>
                <select class="custom-select" id="direction" name="direction">
                    <option value="1" {{ old('course') == 1 ? 'selected' : '' }}>1</option>
                    <option value="2" {{ old('course') == 2 ? 'selected' : '' }}>2</option>
                    <option value="2" {{ old('course') == 3 ? 'selected' : '' }}>3</option>
                    <option value="2" {{ old('course') == 4 ? 'selected' : '' }}>4</option>
                </select>
            </div>
            <div class="form-group">
                <label for="course">Права</label>
                <select class="custom-select" id="course" name="course">
                    <option value="1" {{ old('course') == 1 ? 'selected' : '' }}>1</option>
                    <option value="2" {{ old('course') == 2 ? 'selected' : '' }}>2</option>
                    <option value="2" {{ old('course') == 3 ? 'selected' : '' }}>3</option>
                    <option value="2" {{ old('course') == 4 ? 'selected' : '' }}>4</option>
                </select>
            </div>
            <button type="submit" class="btn">Создать</button>
        </form>
    </div>
    <script src="{{ asset('js/admin/groups/group-create.js') }}"></script>
@endsection
