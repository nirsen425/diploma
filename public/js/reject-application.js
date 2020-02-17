$(function () {
    $('.application-reject-button').click(function () {
        $confirmRejectApplicationButton = $('.confirm-reject-application-button');
        $confirmRejectApplicationButton.attr('type-id', $(this).attr('type-id'));
        $confirmRejectApplicationButton.attr('student-id', $(this).attr('student-id'));
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
                if (status) {
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

                    $('#is-reject-confirmed').modal('show');
                } else {
                    $('#is-reject-failure').modal('show');
                }
            }
        });
    });
});