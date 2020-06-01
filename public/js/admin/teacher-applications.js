$('select[name="year"]').change(function () {
    location.href= "/admin/teacher-applications/" + $(this).find('option:selected').text();
});

$('select[name="teacher-select"]').change(function () {
    location.href = "/admin/teacher-applications/" + $('select[name="year"]').find('option:selected').text()
        + '/' + $(this).find('option:selected').val();
});
