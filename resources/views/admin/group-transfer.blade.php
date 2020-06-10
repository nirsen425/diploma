@extends('admin.base.base')

@section('content')
    <link href="{{ asset('css/admin/group-transfer.css') }}" rel="stylesheet">

    <div id="empty-new-name" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    Не все новые имена заполнены
                </div>
                <div class="modal-footer">
                    <button type="button" class="button" data-dismiss="modal">Ок</button>
                </div>
            </div>
        </div>
    </div>

    <div id="change-success" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    Группы успешно переведены
                </div>
                <div class="modal-footer">
                    <button type="button" class="button" data-dismiss="modal">Ок</button>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white shell p-3" >
        <h3>Перевод групп на новый курс</h3>
        @if (!$groups->isEmpty())
        <div class="alert alert-danger">
            Внимание! Проверьте корректность данных о группах, которые собираетесь перевести на новый курс!
            При переводе групп на новый курс, на странице обновления группы нельзя будет изменить направление и курс,
            а также нельзя будет поменять старые названия переведенных групп.
        </div>
        <table class="table bg-light" id="group-transfer-table">
            <thead class="thead-dark">
                <tr>
                    <th class="d-none">id</th>
                    <th scope="col" class="pl-4">Старое название группы</th>
                    <th scope="col" class="pl-4">Новое название группы</th>
                    <th scope="col" class="pl-4">Год</th>
                    <th scope="col" class="pl-4">Старый курс</th>
                    <th scope="col" class="pl-4">Новый курс / Выпущена</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groups as $group)
                    @php
                        unset($currentCourse);
                        unset($nextCourse);
                        $currentCourse = $group->course()->first()->course;
                        $nextCourse = $currentCourse == 4 ? 'Выпущена' : $currentCourse + 1;
                    @endphp
                    <tr>
                        <td class="d-none group-id">{{ $group->id }}</td>
                        <td class="align-bottom p-4">{{ $group->name }}</td>
                        <td class="align-bottom p-4">
                            @if ($currentCourse != 4)
                                <input type="text" class="new_name">
                            @else
                                Нет
                            @endif
                        </td>
                        <td class="align-bottom p-4">{{ $group->year }}</td>
                        <td class="align-bottom p-4">{{ $currentCourse }}</td>
                        <td class="align-bottom p-4">{{ $nextCourse }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button class="button button-large mt-1 {{ $groups->first()->year == $currentStudyYear ? 'disabled' : '' }}" id="change-сourse">Перевести</button>
        @else
            <h4 class="bg-info p-2 text-white">Нет групп 1-4 курса</h4>
        @endif
    </div>

    <script src="{{ asset('js/admin/group-transfer.js') }}"></script>
@endsection
