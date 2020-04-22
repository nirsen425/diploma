$(document).ready(function () {
    var loadFile = function(event) {
        var teacherImage = document.getElementById('teacherImage');
        teacherImage.src = URL.createObjectURL(event.target.files[0]);

        var $image = $('#teacherImage');
        $image.cropper('destroy');

        $image.cropper({
            aspectRatio: 1 / 1,
            viewMode: 2,
            modal: false,
            background: false,
            zoomable: true,
            responsive: true
        });

        var cropper = $image.data('cropper');

        $('#teacherRegistration').submit(function (event) {
            var $cropData = cropper.getData(true);

            $('#photo_x').val($cropData['x']);
            $('#photo_y').val($cropData['y']);
            $('#photo_width').val($cropData['width']);
            $('#photo_height').val($cropData['height']);
        });
    };

    $('#photo').on('change', loadFile);

    // Текстовый редактор
    CKEDITOR.replace("full_description", {
        filebrowserUploadUrl: "/upload/image",
        extraPlugins: 'uploadimage',
        uploadUrl: '/upload/image'
    });

    var description =  CKEDITOR.instances["full_description"];

    description.on('change', function () {
        CKEDITOR.instances["full_description"].updateElement();
    });

    description.on('blur', function () {
        CKEDITOR.instances["full_description"].updateElement();
        // var validator = $( "#teacherRegistration" ).validate();
        // validator.element("#full_description");
        $('#full_description').blur();
    });

    $('#teacherRegistration').submit(function () {
        CKEDITOR.instances["full_description"].updateElement();
    });

    $.validator.setDefaults({
        // при этой настройке никакие скрытые поля не будут игнорироваться,
        // например текстовая область на которую установлен ckEditor(она скрывается ckEditor'ом)
        ignore: [],
    });

    $.validator.addMethod("smallLetters", function (value, element) {
        var regexp = new RegExp("[a-z]");
        return value.match(regexp);
    }, "Пароль должен содержать минимум одну маленькую английскую букву");

    $.validator.addMethod("bigLetters", function (value, element) {
        var regexp = new RegExp("[A-Z]");
        return value.match(regexp);
    }, "Пароль должен содержать минимум одну большую английскую букву");

    $.validator.addMethod("hasNumber", function (value, element) {
        var regexp = new RegExp("[0-9]");
        return value.match(regexp);
    }, "Пароль должен содержать минимум одну цифру");

    $.validator.addMethod("password", function (value, element) {
        var regexp = new RegExp("^[a-zA-Z0-9]+$");
        return value.match(regexp);
    }, "Пароль некорректен");

    $.validator.addMethod("imageResolution", function (value, element) {
        var $image = $('#teacherImage');
        var cropper = $image.data('cropper');
        var $cropData = cropper.getData(true);
        if ($cropData['width'] >= 200 || $cropData['height'] >= 200) {
            return true;
        }
        return  false;
    }, "Изображение должно быть минимум 200x200");



    $("#teacherRegistration").validate({
        rules: {
            login: {
                required: true,
                minlength: 3,
                remote: {
                    url: "/verification/login/" + $('#login').attr('user-id'),
                    type: "post",
                }
            },
            password: {
                required: function () {
                    if ($("#password").hasClass('d-none')) {
                        return false;
                    }
                    return true;
                },
                minlength:  function () {
                    if ($("#password").hasClass('d-none')) {
                        return false;
                    }
                    return 8;
                },
                smallLetters:  function () {
                    if ($("#password").hasClass('d-none')) {
                        return false;
                    }
                    return true;
                },
                bigLetters:  function () {
                    if ($("#password").hasClass('d-none')) {
                        return false;
                    }
                    return true;
                },
                hasNumber:  function () {
                    if ($("#password").hasClass('d-none')) {
                        return false;
                    }
                    return true;
                },
                password: function () {
                    if ($("#password").hasClass('d-none')) {
                        return false;
                    }
                    return true;
                }
            },
            name: {
                required: true
            },
            patronymic: {
                required: true
            },
            surname: {
                required: true
            },
            short_description: {
                required: true,
                rangelength: [10, 100]
            },
            full_description: {
                required: true,
                minlength: 50,
            },
            photo: {
                imageResolution: function (element) {
                    return element.files[0] !== undefined;
                }
            }
        },
        messages: {
            login: {
                required: "Это поле обязательно для заполнения",
                minlength: "Пожалуйста, введите не менее 3 символов",
                remote: "Этот логин уже занят"
            },
            password: {
                required: "Это поле обязательно для заполнения",
                minlength: "Пожалуйста, введите не менее 8 символов"
            },
            name: {
                required: "Это поле обязательно для заполнения"
            },
            patronymic: {
                required: "Это поле обязательно для заполнения"
            },
            surname: {
                required: "Это поле обязательно для заполнения"
            },
            short_description: {
                required: "Это поле обязательно для заполнения",
                rangelength: "Краткое описание должно состоять минимум из 10 символов и максимум из 100"
            },
            full_description: {
                required: "Это поле обязательно для заполнения",
                minlength: "Полное описание должно состоять минимум из 50 символов"
            },
        },
        // Определение места вставки сообщения об ошибке
        errorPlacement: function(error, element) {
            if (element.attr("name") == "full_description") {
                error.insertAfter("#cke_full_description");
            } else {
                error.insertAfter(element);
            }
        }
    });
});