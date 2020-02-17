$(function () {

    $('.application-approve-button').click(function () {
        $confirmApproveApplicationButton = $('.confirm-approve-application-button');
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

                        let rejectApplicationButton = $('#practice-student .application-reject-button[type-id="' + typeId + '"]' +
                            '[student-id="' + studentId + '"]');

                        rejectApplicationButton.click(passAttributes);
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
                            $confirmRejectApplicationButton = $('.confirm-reject-application-button');
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