$(document).ready(function () {
    $("#groupCreate").validate({
        rules: {
            name: {
                required: true
            },
            direction: {
                required: true
            },
            course: {
                required: true
            },
            students: {
                required: true,
                extension: "csv"
            }
        },
        messages: {
            name: {
                required: "Это поле обязательно для заполнения",
            },
            direction: {
                required: "Это поле обязательно для заполнения",
            },
            course: {
                required: "Это поле обязательно для заполнения"
            },
            students: {
                required: "Это поле обязательно для заполнения",
                extension: "Файл должен иметь формат csv"
            }
        }
    });

    // Загрузка csv
    var loadFile = function(event) {
        if (typeof event.target.files[0] == 'undefined') {
            $('.custom-file-label').text("Выберите файл");
        } else {
            $('.custom-file-label[for="students"]').text(event.target.files[0].name);
        }
        $(this).valid();
    };

    $('#students').on('change', loadFile);
});