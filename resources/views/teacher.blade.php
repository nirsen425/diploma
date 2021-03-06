@extends('base.base')

@section('content')
    <link href="{{ asset('css/teacher.css') }}" rel="stylesheet">
    <div id="confirmSendApplication" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Подтверждение отправки заявки</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Вы действительно хотите отправить заявку?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="button confirm-send-application" data-dismiss="modal">Да</button>
                    <button type="button" class="button" data-dismiss="modal">Отмена</button>
                </div>
            </div>
        </div>
    </div>
    <div id="isConfirmed" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Успех</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="message">Заявка успешно отправлена</p>
                </div>
            </div>
        </div>
    </div>
    <div id="isFailure" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Неудача</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="message">У вас уже есть заявка</p>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-white p-3 rounded teacher-description-container">
        <div class="media">
            <img src="{{ asset("storage/images/" . $teacher->photo) }}" class="mr-3 teacher-photo-description" alt="Преподаватель">
            <div class="media-body full-description text-break">
                {!! $teacher->full_description !!}
                <div class="teacher-limit font-weight-bold">
                    На практику {{ $currentYear }} осталось {{ $teacher->countFreePracticePlaces() }} из {{ $teacher->currentYearPracticeLimits() }} мест
                </div>
                @php
                    $user = Auth::user();
                @endphp
                @if (!isset($confirmApplication))
                    <div class="mt-2">
                        <a href="#"
                           class="button button-large application-button" type_id="1" data-toggle="modal"
                           data-target="#confirmSendApplication">
                            Подать заявку на практику
                        </a>
                    </div>
{{--                    <a href="#"--}}
{{--                       class="button button-large mt-2 mt-lg-0 application-button" type_id="2" data-toggle="modal"--}}
{{--                       data-target="#confirmSendApplication">--}}
{{--                        Подать заявку на диплом--}}
{{--                    </a>--}}
                    <form id="practice-application-form"
                          action="{{ route('application_store', ['teacher' => $teacher->id]) }}"
                          method="POST" class="d-none">
                        {{ csrf_field() }}
                        <input type="hidden" name="type_id" value="1">
                    </form>
{{--                    <form id="diploma-application-form"--}}
{{--                          action="{{ route('application_store', ['teacher' => $teacher->id]) }}"--}}
{{--                          method="POST" class="d-none">--}}
{{--                        {{ csrf_field() }}--}}
{{--                        <input type="hidden" name="type_id" value="2">--}}
{{--                    </form>--}}
                @else
                    <div class="mt-2">
                        <a href="#" class="button button-large disabled">Подать заявку на практику</a>
{{--                    <a href="#" class="button button-large mt-2 mt-lg-0 disabled">Подать заявку на диплом</a>--}}
                    </div>
                    <div class="hint">У вас уже есть подтвержденная заявка</div>
                @endif
            </div>
        </div>
    </div>
    <script src="{{ asset('js/sending-application.js') }}"></script>
@endsection

