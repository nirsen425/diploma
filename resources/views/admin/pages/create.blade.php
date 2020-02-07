@extends('admin.base.base')

@section('content')
    <link href="{{ asset('css/admin/pages/page-create.css') }}" rel="stylesheet">
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div class="content">
        <h3>Создание страницы</h3>
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    {{ $error }}
                    <br>
                @endforeach
            </div>
        @endif
        <form action="{{ route('pages.store') }}" method="POST" id="pageRegistration">
            @csrf

            <div class="form-group">
                <label for="title">Заголовок</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}">
            </div>
            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="show" name="show" value="1" {{ old('show') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="show">Показывать страницу на сайте</label>
                </div>
            </div>
            <div class="form-group">
                <label for="content">Контент страницы</label>
                <textarea class="form-control" id="content" name="content">{{ old('content') }}</textarea>
            </div>
            <div class="form-group">
                <label for="meta_headline">Мета заголовок</label>
                <input type="text" class="form-control" id="meta_headline" name="meta_headline" value="{{ old('meta_headline') }}">
            </div>
            <div class="form-group">
                <label for="meta_description">Мета описание</label>
                <input type="text" class="form-control" id="meta_description" name="meta_description" value="{{ old('meta_description') }}">
            </div>
            <div class="form-group">
                <label for="meta_words">Ключевые слова</label>
                <input type="text" class="form-control" id="meta_words" placeholder="Иванов" name="meta_words" value="{{ old('meta_words') }}">
            </div>
            <button type="submit" class="btn btn-danger">Создать</button>
        </form>
    </div>
    <script src="{{ asset('js/admin/pages/page-active-submenu.js') }}"></script>
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('js/admin/pages/page-create.js') }}"></script>
@endsection

