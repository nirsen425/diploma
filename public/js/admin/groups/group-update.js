$(document).ready(function () {
    $("#groupUpdate").validate({
        rules: {
            direction: {
                required: true
            },
            students: {
                extension: "csv"
            },
            name: {
                required: true,
                remote: {
                    url: "/verification/group-name/" + $('#name').attr('group-id'),
                    type: "post",
                }
            }
        },
        messages: {
            direction: {
                required: "Это поле обязательно для заполнения",
            },
            students: {
                extension: "Файл должен иметь формат csv"
            },
            name: {
                required: true,
                remote: "Группа с таким названием уже существует или существовала"
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