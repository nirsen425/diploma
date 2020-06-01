$(document).ready(function () {

    $('select[name="direction"]').change(function () {
        location.href= "/admin/practice-info/" + $(this).find('option:selected').val();
    });

    $('select[name="course"]').change(function () {
        location.href = "/admin/practice-info/" + $('select[name="direction"]').find('option:selected').val()
            + '/' + $(this).find('option:selected').val();
    });

    // Текстовый редактор
    CKEDITOR.replace("practice_info", {
        filebrowserUploadUrl: "/upload/image",
        extraPlugins: 'uploadimage',
        uploadUrl: '/upload/image'
    });

    var practiceInfo =  CKEDITOR.instances["practice_info"];

    practiceInfo.on('change', function () {
        CKEDITOR.instances["practice_info"].updateElement();
    });

    practiceInfo.on('blur', function () {
        CKEDITOR.instances["practice_info"].updateElement();
        $('#full_description').blur();
    });

    $('#practiceInfoEdit').submit(function () {
        CKEDITOR.instances["practice_info"].updateElement();
    });

});
