$(function () {
    $('#change-сourse').click(function () {
        let groupsArray = new Array();

        $countEmptyNewNameField = $('.new_name').filter(function () {
            return $.trim(this.value) == '';
        }).length;

        if ($countEmptyNewNameField > 0) {
            $('#empty-new-name').modal('show');
            return;
        }

        $('#group-transfer-table tbody tr').each(function () {
            let groupObject = new Object();
            groupObject['group_id'] = $(this).find('.group-id').text();
            groupObject['new_name'] = $(this).find('.new_name').val();
            groupsArray.push(groupObject);
        });
        console.log(new Date().getFullYear());
        $.ajax({
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {groupsArray: groupsArray },
            url: "/admin/group-transfer",
            success: function (year) {
                if (year) {
                    $date = new Date();
                    if (year == ($date.getMonth() < 6 ? $date.getFullYear() - 1 : $date.getFullYear())) {
                        $('#change-сourse').addClass('disabled');
                    }
                    $('#change-success').modal('show');
                }
            }
        });
    });
});