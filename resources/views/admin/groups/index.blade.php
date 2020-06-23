@extends('admin.base.base')

@section('content')
    <link href="{{ asset('css/admin/groups/group-index.css') }}" rel="stylesheet">
    <div class="bg-white bg-light shell p-3">
        <h3>Список групп</h3>
        @if (!$groups->isEmpty())
            <table class="table group-table bg-light mt-3">
                <thead class="thead-dark">
                <tr>
                    <th scope="col" class="pl-4">Название</th>
                    <th scope="col" class="pl-4">Год</th>
                    <th scope="col" class="pl-4">Направление</th>
                    <th scope="col" class="pl-4">Курс</th>
                    <th scope="col" class="pl-4">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($groups as $group)
                    <tr>
                        <td class="p-4">{{ $group->name }}</td>
                        <td class="p-4">{{ $group->year}}</td>
                        <td class="p-4">{{ $group->direction()->first()->direction }}</td>
                        <td class="p-4">{{ $group->course()->first()->course }}</td>
                        <td class="p-4">
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
        @else
            <h4 class="bg-info p-2 text-white">Нет групп</h4>
        @endif
    </div>
    
    <script src="{{ asset('js/admin/groups/group-active-submenu.js') }}"></script>
@endsection
