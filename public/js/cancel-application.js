$(function () {
    $('.application-cancel-button').click(function () {
        $confirmCancelApplicationButton = $('.confirm-cancel-application-button');
        $confirmCancelApplicationButton.attr('type-id', $(this).attr('type-id'));
        $confirmCancelApplicationButton.attr('teacher-id', $(this).attr('teacher-id'));
    });

    $('.confirm-cancel-application-button').click(function () {
        var typeId = $(this).attr('type-id');
        var teacherId = $(this).attr('teacher-id');

        $.ajax({
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/application/cancel/" + teacherId + "/" + typeId,
            success: function(status) {
                if (status) {
                    $('#is-cancel-confirmed').modal('show');
                    let canceledApplication = $('.application-cancel-button[type-id="' + typeId + '"]' +
                        '[teacher-id="' + teacherId + '"]').closest('.request');
                    canceledApplication.remove();

                    let requestContainer = $('#current-request .request-container');

                    if (!requestContainer.children().length) {
                        requestContainer.html('<div class="pt-3">У вас нет заявок</div>');
                    }
                } else {
                    $('#is-cancel-failure').modal('show');
                }
            }
        });
    });
});