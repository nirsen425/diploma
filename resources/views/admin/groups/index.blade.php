@extends('admin.base.base')

@section('content')
    <link href="{{ asset('css/admin/groups/group-index.css') }}" rel="stylesheet">

    <table class="table group-table">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Название</th>
            <th scope="col">Год</th>
            <th scope="col">Направление</th>
            <th scope="col">Курс</th>
            <th scope="col">Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($groups as $group)
            <tr>
                <td>{{ $group->name }}</td>
                <td>{{ $group->year}}</td>
                <td>{{ $group->direction()->first()->direction }}</td>
                <td>{{ $group->course()->first()->course }}</td>
                <td>
                    <a href="{{ route('groups.show', ['group' => $group->id]) }}">
                        <i class="far fa-eye"></i>
                    </a>
                    <a href="{{ route('groups.edit', ['group' => $group->id]) }}">
                        <i class="far fa-edit"></i>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <script src="{{ asset('js/admin/groups/group-active-submenu.js') }}"></script>
@endsection
