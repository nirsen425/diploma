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
            <img src="http://images6.fanpop.com/image/photos/35100000/Emma-Watson-Icons-emma-watson-35139263-200-200.jpg" class="mr-3 teacher-photo-description" alt="Преподаватель">
            <div class="media-body text-break">
                {!! $teacher->full_description !!}
                @php
                    $user = Auth::user();
                @endphp
                @if ($user and $user->user_type_id == 1)
                    <a href="#"
                       class="button button-large application-button" type_id="1" data-toggle="modal"
                       data-target="#confirmSendApplication">
                        Подать заявку на практику
                    </a>
                    <a href="#"
                       class="button button-large mt-2 mt-lg-0 application-button" type_id="2" data-toggle="modal"
                       data-target="#confirmSendApplication">
                        Подать заявку на диплом
                    </a>
                    <form id="practice-application-form"
                          action="{{ route('application_store', ['teacher' => $teacher->id]) }}"
                          method="POST" class="d-none">
                        {{ csrf_field() }}
                        <input type="hidden" name="type_id" value="1">
                    </form>
                    <form id="diploma-application-form"
                          action="{{ route('application_store', ['teacher' => $teacher->id]) }}"
                          method="POST" class="d-none">
                        {{ csrf_field() }}
                        <input type="hidden" name="type_id" value="2">
                    </form>
                @else
                    <a href="#" class="button button-large disabled">Подать заявку на практику</a>
                    <a href="#" class="button button-large mt-2 mt-lg-0 disabled">Подать заявку на диплом</a>
                    <div class="hint">Только авторизованные студенты могут подавать заявки</div>
                @endif
            </div>
        </div>
    </div>
    <script src="{{ asset('js/sending-application.js') }}"></script>
@endsection

