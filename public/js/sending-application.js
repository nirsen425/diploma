$(function () {
    $('.application-button').click(function () {

        $('.confirm-send-application').attr('type_id', $(this).attr('type_id'));
    });

    $('.confirm-send-application').click(function () {
        var typeId = $(this).attr('type_id');
        var form = $('[name="type_id"][value="' + typeId + '"]').closest('form');
        $.ajax({
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: form.attr('action'),
            data: form.serialize(),
            success: function(response) {
                var response = JSON.parse(response);
                if (response['status']) {
                    $('#isConfirmed .message').text(response['message']);
                    $('#isConfirmed').modal('show');
                } else {
                    $('#isFailure .message').text(response['message']);
                    $('#isFailure').modal('show');
                }
            }
        });
    });
});