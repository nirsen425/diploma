$(document).ready(function () {
    $('.delete').click(function () {
        $('#confirmDelete .confirm-delete').attr('direction-id', $(this).attr('direction-id'));
        $('#confirmDelete .confirm-delete').attr('course-id', $(this).attr('course-id'));
        $('#confirmDelete .confirm-delete').attr('file-id', $(this).attr('file-id'));
    });

    $('#confirmDelete .confirm-delete').click(function () {

        var directionId = $(this).attr('direction-id');
        var courseId = $(this).attr('course-id');
        var fileId = $(this).attr('file-id');
        var fileTrElement =  $('.delete[file-id="' + fileId + '"]').closest('tr');

        $.ajax({
            url: '/admin/file/delete/' + directionId + '/' + courseId + '/' + fileId,
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
