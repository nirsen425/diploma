$('select[name="year"]').change(function () {
    location.href= "/admin/student-applications/" + $(this).find('option:selected').text();
});

$('select[name="group"]').change(function () {
    location.href = "/admin/student-applications/" + $('select[name="year"]').find('option:selected').text()
        + '/' + $(this).find('option:selected').val();
});

$('select[name="teacher"]').change(function () {
    let selectStatus = $(this).closest('tr').find('td select[name="status"]')

    selectStatus.html('    <option class="d-none"></option>\n' +
        '    <option value="1">Ожидание</option>\n' +
        '    <option value="2">Подтверждена</option>\n' +
        '    <option value="3">Отклонена</option>');
    selectStatus.focus();
});

$(function () {
    SwitchButton();
});

$('select[name="status"]').change(function () {
    SwitchButton();
    let teacherId = $(this).closest('tr').find('td select[name="teacher"] option:selected').val();
    let studentId = $(this).closest('tr').find('.student-id').text();
    let statusId = $(this).find('option:selected').val();
    let year = $('select[name="year"]').find('option:selected').text();
    let groupId = $('select[name="group"]').find('option:selected').val();
    let applictionDataObject = {
        teacherId: teacherId,
        studentId: studentId,
        statusId: statusId,
        groupId: groupId,
        year: year
    };

    $.ajax({
        type: 'post',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {'applictionDataArray': applictionDataObject},
        url: "/admin/student-applications",
        success: function(status) {
            if (status) {
                $('#change-success').modal('show');
            }
        }
    });
});

// подсчет подтвержденных заявок для де/активации кнопки отчета
function SwitchButton() {
    let applicationCount = document.getElementsByName('status').length;
    let confirmApplicationCount = 0;
    $('select[name="status"]').each(function () {
        if($(this).val() === "2") {
            confirmApplicationCount++;
        }
    });
    if(applicationCount === confirmApplicationCount)
    {
        $('#student-report').removeClass('disabled');
    }
    else {
        $('#student-report').addClass("disabled");
    }
}
