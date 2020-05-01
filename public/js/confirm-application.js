$(function () {

    $('.application-approve-button').click(function () {
        var $confirmApproveApplicationButton = $('.confirm-approve-application-button');
        $confirmApproveApplicationButton.attr('type-id', $(this).attr('type-id'));
        $confirmApproveApplicationButton.attr('student-id', $(this).attr('student-id'));
    });

    $('.confirm-approve-application-button').click(function () {
        var typeId = $(this).attr('type-id');
        var studentId = $(this).attr('student-id');

        $.ajax({
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/application/confirm/" + studentId+ "/" + typeId,
            success: function(status) {
                if (status) {
                    let confirmedApplication = $('.application-approve-button[type-id="' + typeId + '"]' +
                        '[student-id="' + studentId + '"]').closest(".request");
                    var requestName = confirmedApplication.find('.request-name').text();
                    confirmedApplication.remove();

                    var practiceStudentRequestContainer = $('#practice-student .request-container');
                    var diplomaStudentRequestContainer = $('#diploma-student .request-container');
                    var newRequestContainer = $('#new-request .request-container');

                    if (!newRequestContainer.children().length) {
                        newRequestContainer.html('<div class="pt-3 no-request">У вас нет заявок</div>');
                    }

                    if (typeId == 1) {
                        if (practiceStudentRequestContainer.find('.no-request').length) {
                            practiceStudentRequestContainer.html('<div class="request font-weight-bolder">' +
                                '<div class="request-name">'+ requestName + '</div>' +
                                '   <button class="button application-reject-button"' +
                                '       student-id="'+ studentId + '"' +
                                '       type-id="1" data-toggle="modal"' +
                                '       data-target="#confirm-reject-application">' +
                                '       Отклонить' +
                                '   </button>' +
                                '</div>');
                        } else {
                            practiceStudentRequestContainer.append('<div class="request font-weight-bolder">' +
                                '<div class="request-name">'+ requestName + '</div>' +
                                '   <button class="button application-reject-button"' +
                                '       student-id="'+ studentId + '"' +
                                '       type-id="1" data-toggle="modal"' +
                                '       data-target="#confirm-reject-application">' +
                                '       Отклонить' +
                                '   </button>' +
                                '</div>');
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
                                placesNumberForm.text(declOfNum(countPlaces, ['место', 'места', 'мест']));

                                function declOfNum(number, titles) {
                                    cases = [2, 0, 1, 1, 1, 2];
                                    return titles[ (number%100>4 && number%100<20)? 2 : cases[(number%10<5)?number%10:5] ];
                                }
                            }
                        });

                        let rejectApplicationButton = $('#practice-student .application-reject-button[type-id="' + typeId + '"]' +
                            '[student-id="' + studentId + '"]');

                        rejectApplicationButton.click(function () {
                            let $confirmRejectApplicationButton = $('.confirm-reject-application-button');
                            $confirmRejectApplicationButton.attr('type-id', $(this).attr('type-id'));
                            $confirmRejectApplicationButton.attr('student-id', $(this).attr('student-id'));
                            $confirmRejectApplicationButton.attr('countable', 'yes');
                        });
                    }

                    if (typeId == 2) {
                        if (diplomaStudentRequestContainer.find('.no-request').length) {
                            diplomaStudentRequestContainer.html('<div class="request font-weight-bolder">' +
                                '<div class="request-name">'+ requestName + '</div>' +
                                '   <button class="button application-reject-button"' +
                                '       student-id="'+ studentId + '"' +
                                '       type-id="2" data-toggle="modal"' +
                                '       data-target="#confirm-reject-application">' +
                                '       Отклонить' +
                                '   </button>' +
                                '</div>');
                        } else {
                            diplomaStudentRequestContainer.append('<div class="request font-weight-bolder">' +
                                '<div class="request-name">'+ requestName + '</div>' +
                                '   <button class="button application-reject-button"' +
                                '       student-id="'+ studentId + '"' +
                                '       type-id="2" data-toggle="modal"' +
                                '       data-target="#confirm-reject-application">' +
                                '       Отклонить' +
                                '   </button>' +
                                '</div>');
                        }

                        let rejectApplicationButton = $('#diploma-student .application-reject-button[type-id="' + typeId + '"]' +
                            '[student-id="' + studentId + '"]');


                        rejectApplicationButton.click(function () {
                            let $confirmRejectApplicationButton = $('.confirm-reject-application-button');
                            $confirmRejectApplicationButton.attr('type-id', $(this).attr('type-id'));
                            $confirmRejectApplicationButton.attr('student-id', $(this).attr('student-id'));
                        });
                    }

                    $('#is-approve-confirmed').modal('show');
                } else {
                    $('#is-reject-failure').modal('show');
                }
            }
        });
    });
});