$(function () {
    // При нажатие кнопки отмены заявки, нужно подтвердить отказ в модальном окне,
    // нажав на кнопку подтверждения отказа в модальном окне, поэтому переносим
    // информацию с кнопки отмены заявки на кнопку в модальном окне, чтобы
    // понимать у какого студента отменяем заявку в дальнейшем
    $('#new-request .application-reject-button').click(function () {
        let $confirmRejectApplicationButton = $('.confirm-reject-application-button');
        $confirmRejectApplicationButton.attr('type-id', $(this).attr('type-id'));
        $confirmRejectApplicationButton.attr('student-id', $(this).attr('student-id'));
        $confirmRejectApplicationButton.removeAttr('countable');
    });

    // При нажатие кнопки отмены заявки, нужно подтвердить отказ в модальном окне,
    // нажав на кнопку подтверждения отказа в модальном окне, поэтому переносим
    // информацию с кнопки отмены заявки на кнопку в модальном окне, чтобы
    // понимать у какого студента отменяем заявку в дальнейшем
    $('#practice-student .application-reject-button').click(function () {
        let $confirmRejectApplicationButton = $('.confirm-reject-application-button');
        $confirmRejectApplicationButton.attr('type-id', $(this).attr('type-id'));
        $confirmRejectApplicationButton.attr('student-id', $(this).attr('student-id'));
        $confirmRejectApplicationButton.attr('countable', 'yes');
    });

    $('.confirm-reject-application-button').click(function () {
        var typeId = $(this).attr('type-id');
        var studentId = $(this).attr('student-id');

        $.ajax({
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/application/reject/" + studentId+ "/" + typeId,
            success: function(status) {
                if (status == "true") {
                    // Удаление заявки студента, которую отклонили
                    let rejectedApplication = $('.application-reject-button[type-id="' + typeId + '"]' +
                        '[student-id="' + studentId + '"]').closest(".request");
                    rejectedApplication.remove();

                    var practiceStudentRequestContainer = $('#practice-student .request-container');
                    var diplomaStudentRequestContainer = $('#diploma-student .request-container');
                    var newRequestContainer = $('#new-request .request-container');

                    if (!practiceStudentRequestContainer.children().length) {
                        practiceStudentRequestContainer.html('<div class="pt-3 no-request">У вас нет практикантов</div>');
                    }
                    if (!diplomaStudentRequestContainer.children().length) {
                        diplomaStudentRequestContainer.html('<div class="pt-3 no-request">У вас нет сдающих диплом</div>');
                    }

                    if (!newRequestContainer.children().length) {
                        newRequestContainer.html('<div class="pt-3 no-request">У вас нет заявок</div>');
                    }

                    $.ajax({
                        type: 'post',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "/application/get-free-practice-places",
                        success: function(countPlaces) {
                            var countPlaces = countPlaces;
                            let freePracticePlaces = $('#count-practice-places #free-practice-places');
                            freePracticePlaces.text(countPlaces);
                            let placesNumberForm = $('#count-practice-places #places-number-form');
                            placesNumberForm.text(getСorrectFormWord(countPlaces, ['место', 'места', 'мест']));

                            function getСorrectFormWord(number, titles) {
                                cases = [2, 0, 1, 1, 1, 2];
                                return titles[ (number%100>4 && number%100<20)? 2 : cases[(number%10<5)?number%10:5] ];
                            }
                        }
                    });

                    $('#is-reject-confirmed').modal('show');
                } else {
                    $('#is-reject-failure').modal('show');
                }
            }
        });
    });
});