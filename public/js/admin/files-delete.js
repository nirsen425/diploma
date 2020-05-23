$(document).ready(function () {
    $('.delete').click(function () {
        $('#confirmDelete .confirm-delete').attr('file-id', $(this).attr('file-id'));
    });

    $('#confirmDelete .confirm-delete').click(function () {

        var fileId = $(this).attr('file-id');
        var fileTrElement =  $('.delete[file-id="' + fileId + '"]').closest('tr');

        $.ajax({
            url: '/admin/file/delete/' + fileId,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(status) {
                var resStatus = JSON.parse(status);
                if (resStatus['status']) {
                    $(fileTrElement).remove();
                    $('#isDeleted').modal('show');
                } else {
                    $('#isFailure').modal('show');
                }
            }
        });
    });
});
