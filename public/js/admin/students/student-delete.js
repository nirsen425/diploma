$(function () {
    // При нажатие кнопку удаления студента, нужно подтвердить удаление в модальном окне,
    // нажав на кнопку подтверждения удаления в модальном окне, поэтому переносим
    // информацию с кнопки удаления студента на кнопку в модальном окне, чтобы
    // понимать какого студента удалить
    $('.delete').click(function () {
        $('#confirmDelete .confirm-delete').attr('student-id', $(this).attr('student-id'));
    });

    $('#confirmDelete .confirm-delete').click(function () {

        var studentId = $(this).attr('student-id');
        var studentTrElement =  $('.delete[student-id="' + studentId + '"]').closest('tr');

        $.ajax({
            type: 'delete',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/admin/students/' + studentId,
            success: function(status) {
                if (status) {
                    $(studentTrElement).remove();
                    $('#isDeleted').modal('show');
                } else {
                    $('#isFailure').modal('show');
                }
            }
        });
    });
});
