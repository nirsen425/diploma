$(document).ready(function () {

    $('select[name="direction"]').change(function () {
        location.href= "/admin/practice-info/" + $(this).find('option:selected').val();
    });

    $('select[name="course"]').change(function () {
        location.href = "/admin/practice-info/" + $('select[name="direction"]').find('option:selected').val()
            + '/' + $(this).find('option:selected').val();
    });

    if($('select[name="course"]').val()!="") {
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
            $('#practice_info').blur();
        });

        $('#practiceInfoEdit').submit(function () {
            CKEDITOR.instances["practice_info"].updateElement();
        });
    }

    $.validator.setDefaults({
        ignore: [],
    });

    $("#practiceInfoEdit").validate({
        rules: {
            application_start: {
                required: true,
                maxlength: 10
            },
            application_end: {
                required: true,
                maxlength: 10
            },
            practice_start: {
                required: true,
                maxlength: 10
            },
            practice_end: {
                required: true,
                maxlength: 10
            },
            practice_info: {
                required: true,
            },
        },
        messages: {
            application_start: {
                required: "Это поле обязательно для заполнения",
                maxlength: "Год должен состоять из 4 цифр",
            },
            application_end: {
                required: "Это поле обязательно для заполнения",
                maxlength: "Год должен состоять из 4 цифр",
            },
            practice_start: {
                required: "Это поле обязательно для заполнения",
                maxlength: "Год должен состоять из 4 цифр",
            },
            practice_end: {
                required: "Это поле обязательно для заполнения",
                maxlength: "Год должен состоять из 4 цифр",
            },
            practice_info: {
                required: "Это поле обязательно для заполнения",
            },
        },
    });
});
