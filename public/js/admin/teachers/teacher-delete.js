$(function () {
    // При нажатие кнопку удаления преподавателя, нужно подтвердить удаление в модальном окне,
    // нажав на кнопку подтверждения удаления в модальном окне, поэтому переносим
    // информацию с кнопки удаления преподавателя на кнопку в модальном окне, чтобы
    // понимать какого преподавателя удалить
    $('.delete').click(function () {
        $('#confirmDelete .confirm-delete').attr('teacher-id', $(this).attr('teacher-id'));
    });

   $('#confirmDelete .confirm-delete').click(function () {

       var teacherId = $(this).attr('teacher-id');
       var teacherTrElement =  $('.delete[teacher-id="' + teacherId + '"]').closest('tr');

        $.ajax({
            type: 'delete',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/admin/lecturers/' + teacherId,
            success: function(status) {
                if (status) {
                    $(teacherTrElement).remove();
                    $('#isDeleted').modal('show');
                } else {
                    $('#isFailure').modal('show');
                }
            }
        });
   });
});
