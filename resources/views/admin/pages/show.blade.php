@extends('admin.base.base')

@section('content')
    <link href="{{ asset('css/admin/pages/page-show.css') }}" rel="stylesheet">
    <div class="content shell text-break">
        <h3 class="mb-3">Просмотр страницы</h3>
        <div class="font-weight-bold">Заголовок</div>
        <h5 class="mt-0">{{ $page->title }}</h5>
        <div class="font-weight-bold">Показывать страницу на сайте</div>
        <div>{{ $page->show ? "Да" : "Нет" }}</div>
        <div class="font-weight-bold">Контент страницы</div>
        <div>{!! $page->content !!}</div>
        <div class="font-weight-bold">Мета заголовок</div>
        <div>{{ $page->meta_headline }}</div>
        <div class="font-weight-bold">Мета описание</div>
        <div>{{ $page->meta_description}}</div>
        <div class="font-weight-bold">Ключевые слова</div>
        <div>{{ $page->meta_words}}</div>
    </div>
    <script src="{{ asset('js/admin/students/student-active-submenu.js') }}"></script>
@endsection
