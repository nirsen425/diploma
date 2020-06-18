@extends('admin.base.base')

@section('content')
    <link href="{{ asset('css/admin/groups/group-show.css') }}" rel="stylesheet">
    <div class="bg-white shell p-3">
        <div class="d-flex justify-content-between">
            <h3>{{ $group->name }}</h3>
            <a href="{{ route('report_login_student', ['group' => $group->id]) }}" class="button button-large"
               id="generate-report">Сформировать отчет по логинам</a>
        </div>
        <div class="d-flex group-info">
            <div class="pt-3 pb-3 pr-3">
                <div class="font-weight-bold">Год</div>
                <div>{{ $group->year }}</div>
            </div>
            <div class="p-3">
                <div class="font-weight-bold">Направление</div>
                <div>{{ $group->direction()->first()->direction }}</div>
            </div>
            <div class="p-3">
                <div class="font-weight-bold">Курс</div>
                <div>{{ $group->course()->first()->course }}</div>
            </div>
        </div>
        <table class="table bg-light student-table">
            <thead class="thead-dark">
            <tr>
                <th scope="col" class="pl-4">Персональный номер</th>
                <th scope="col" class="pl-4">ФИО</th>
                <th scope="col" class="pl-4">Логин</th>
                <th scope="col" class="pl-4">Email</th>
            </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <tr>
                        <td class="p-4">{{ $student->personal_number }}</td>
                        <td class="p-4">{{ $student->getFullName()}}</td>
                        <td class="p-4">{{ $student->user()->first()->login }}</td>
                        <td class="p-4">{{ $student->user()->first()->email ? $student->user()->first()->email : 'Нет' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="{{ asset('js/admin/ie-edge-min-width-shell.js') }}"></script>
    <script src="{{ asset('js/admin/groups/group-active-submenu.js') }}"></script>
@endsection
