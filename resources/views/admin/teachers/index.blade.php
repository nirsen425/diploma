@extends('admin.base.base')

@section('content')
    <style>
        .table td {
            vertical-align: middle;
        }
        .teacher-table {
            background-color: #fff;
        }
    </style>
    <table class="table teacher-table">
        <thead class="thead-dark">
        <tr>
            <th scope="col ">Фото</th>
            <th scope="col">Имя</th>
            <th scope="col">Фамилия</th>
            <th scope="col">Отчество</th>
            <th scope="col">Действия</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><img src="https://picsum.photos/100" alt=""></td>
            <td>Mark</td>
            <td>Otto</td>
            <td>@mdo</td>
            <td>
                <a href="{{ route('lecturers.show', ['lecturer' => 1]) }}">
                    <i class="far fa-eye"></i>
                </a>
                <a href="{{ route('lecturers.edit', ['lecturer' => 1]) }}">
                    <i class="far fa-edit"></i>
                </a>
                <a href="{{ route('lecturers.destroy', ['lecturer' => 1]) }}">
                    <i class="fas fa-trash-alt"></i>
                </a>
            </td>
        </tr>
        <tr>
            <td><img src="https://picsum.photos/100" alt=""></td>
            <td>Mark</td>
            <td>Otto</td>
            <td>@mdo</td>
            <td>@mdo</td>
        </tr>
        <tr>
            <td><img src="https://picsum.photos/100" alt=""></td>
            <td>Mark</td>
            <td>Otto</td>
            <td>@mdo</td>
            <td>@mdo</td>
        </tr>
        </tbody>
    </table>
@endsection
