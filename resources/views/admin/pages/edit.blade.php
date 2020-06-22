@extends('admin.base.base')

@section('content')
    <link href="{{ asset('css/admin/pages/page-update.css') }}" rel="stylesheet">
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div class="content shell text-break">
        <h3 class="mb-4">Редактирование страницы</h3>
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    {{ $error }}
                    <br>
                @endforeach
            </div>
        @endif

        <form action="{{ route('pages.update', ['page' => $page->id]) }}" method="POST" id="pageUpdate">
            @csrf
            <input type="hidden" name="_method" value="PUT">


            <div class="form-group row">
                <label for="title" class="col-lg-2 col-form-label font-weight-bold">Заголовок</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" id="title" name="title" page-id="{{ $page->id }}" value="{{ $page->title }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-2"></div>
                <div class="col-lg-10">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="show" name="show" value="1" {{ $page->show ? 'checked' : '' }}>
                        <label class="custom-control-label" for="show">Показывать страницу на сайте</label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="content" class="col-lg-2 col-form-label text-break font-weight-bold">Контент страницы</label>
                <div class="col-lg-10">
                    <textarea class="form-control" id="content" name="content">{{ $page->content }}</textarea>
                </div>
            </div>
            <div class="form-group row">
                <label for="meta_headline" class="col-lg-2 col-form-label font-weight-bold">Мета заголовок</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" id="meta_headline" placeholder="Иванов" name="meta_headline" value="{{ $page->meta_headline }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="meta_description" class="col-lg-2 font-weight-bold">Мета описание</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" id="meta_description" placeholder="Иванов" name="meta_description" value="{{ $page->meta_description }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="meta_words" class="col-lg-2 font-weight-bold">Ключевые слова</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" id="meta_words" name="meta_words" value="{{ $page->meta_words }}">
                </div>
            </div>
            <button type="submit" class="button button-large">Изменить</button>
        </form>
    </div>
    <script src="{{ asset('js/admin/pages/page-active-submenu.js') }}"></script>
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('js/admin/pages/page-update.js') }}"></script>
@endsection

