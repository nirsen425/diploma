$(function () {
    // При нажатие кнопку удаления страницы, нужно подтвердить удаление в модальном окне,
    // нажав на кнопку подтверждения удаления в модальном окне, поэтому переносим
    // информацию с кнопки удаления страницы на кнопку в модальном окне, чтобы
    // понимать какую страницу удалить
    $('.delete').click(function () {
        $('#confirmDelete .confirm-delete').attr('page-id', $(this).attr('page-id'));
    });

    $('#confirmDelete .confirm-delete').click(function () {

        var pageId = $(this).attr('page-id');
        var pageTrElement =  $('.delete[page-id="' + pageId + '"]').closest('tr');

        $.ajax({
            type: 'delete',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/admin/pages/' + pageId,
            success: function(status) {
                if (status) {
                    $(pageTrElement).remove();
                    $('#isDeleted').modal('show');
                } else {
                    $('#isFailure').modal('show');
                }
            }
        });
    });
});