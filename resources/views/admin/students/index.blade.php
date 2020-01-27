{{--@extends('admin.base.base')--}}

{{--@section('content')--}}
{{--    <p>Это содержимое тела страницы.</p>--}}
{{--@endsection--}}
        <!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <title>Document</title>
</head>
<body>
<style>
    .table td {
        vertical-align: middle;
    }

    div {
        padding: 40px;
        background-color: white;
    }
</style>
<div>
<table class="table">
    <thead class="thead-dark">
    <tr>
        <th scope="col ">Фото</th>
        <th scope="col">Логин</th>
        <th scope="col">Временный пароль</th>
        <th scope="col">Имя</th>
        <th scope="col">Фамилия</th>
        <th scope="col">Отчество</th>
        <th scope="col">Статус аккаунта</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><img src="https://picsum.photos/100" alt=""></td>
        <td>Mark</td>
        <td>Mark</td>
        <td>Otto</td>
        <td>@mdo</td>
        <td>Otto</td>
        <td>@mdo</td>
    </tr>
    <tr>
        <td><img src="https://picsum.photos/100" alt=""></td>
        <td>Mark</td>
        <td>Mark</td>
        <td>Otto</td>
        <td>@mdo</td>
        <td>Otto</td>
        <td>@mdo</td>
    </tr>
    <tr>
        <td><img src="https://picsum.photos/100" alt=""></td>
        <td>Mark</td>
        <td>Mark</td>
        <td>Otto</td>
        <td>@mdo</td>
        <td>Otto</td>
        <td>@mdo</td>
    </tr>
    </tbody>
</table>
</div>
</body>
</html>
