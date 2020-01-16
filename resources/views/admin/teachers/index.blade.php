@extends('admin.base.base')

@section('content')
    <style>
        .teacher-photo {
            height: 140px;
        }
    </style>
    <h3>Список преподавателей</h3>
    <div class="row no-gutters mt-2">
        <div class="col d-flex justify-content-start bg-light p-3">
{{--            style="height: 100% !important;"--}}
            <img class="teacher-photo" src="{{ asset('images/avatar.png') }}" alt="">
            <div class="ml-3 bg-light text-dark rounded-right">
                <h5 class="mb-2">ФИО</h5>
                <p class="mb-2 text-dark">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                <p><small class="text-muted">Last updated 3 mins ago</small></p>
                <button type="button" class="btn btn-danger">Подробнее</button>
            </div>
        </div>
    </div>
@endsection
