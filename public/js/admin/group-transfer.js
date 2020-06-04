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

        $.ajax({
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {groupsArray: groupsArray },
            url: "/admin/group-transfer",
            success: function (status) {
                if (status) {
                    $('#change-success').modal('show');
                }
            }
        });
    });
});