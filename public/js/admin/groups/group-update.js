$(document).ready(function () {
    $("#groupCreate").validate({
        rules: {
            direction: {
                required: true
            },
            students: {
                required: true,
                extension: "csv"
            }
        },
        messages: {
            direction: {
                required: "Это поле обязательно для заполнения",
            },
            students: {
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