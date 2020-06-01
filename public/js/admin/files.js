$('select[name="direction"]').change(function () {
    location.href= "/admin/files/" + $(this).find('option:selected').val();
});

$('select[name="course"]').change(function () {
    location.href = "/admin/files/" + $('select[name="direction"]').find('option:selected').val()
        + '/' + $(this).find('option:selected').val();
});
