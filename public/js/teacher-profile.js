$(function () {
    $(".toggle-login-form").click(function () {
        $('#login-update').slideToggle();
        $(this).toggleClass('fa-arrow-up').toggleClass('fa-arrow-down');
    });

    $(".toggle-password-form").click(function () {
        $('#password-update').slideToggle();
        $(this).toggleClass('fa-arrow-up').toggleClass('fa-arrow-down');
    });

    $(".toggle-photo-form").click(function () {
        $('#photo-update').slideToggle();
        $(this).toggleClass('fa-arrow-up').toggleClass('fa-arrow-down');
    });

    $(".toggle-short-description-form").click(function () {
        $('#short-description-update').slideToggle();
        $(this).toggleClass('fa-arrow-up').toggleClass('fa-arrow-down');
    });

    $(".toggle-full-description-form").click(function () {
        $('#full-description-update').slideToggle();
        $(this).toggleClass('fa-arrow-up').toggleClass('fa-arrow-down');
    });

    $(".toggle-email-form").click(function () {
        $('#email-update').slideToggle();
        $('.toggle-email-form').toggleClass('fa-arrow-up').toggleClass('fa-arrow-down');
    });

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
        $('#full_description').blur();
    });

    // Валидация
    $.validator.setDefaults({
        // при этой настройке никакие скрытые поля не будут игнорироваться,
        // например текстовая область на которую установлен ckEditor(она скрывается ckEditor'ом)
        ignore: [],
    });

    $.validator.addMethod("login", function (value, element) {
        var regexp = new RegExp("^[a-zA-Z0-9]+$");
        return value.match(regexp);
    }, "Логин может содержать только цифры и латинские буквы");

    $.validator.addMethod("pwcheck", function (value, element) {
        var regexp = new RegExp("^[a-zA-Z0-9]+$");
        return value.match(regexp);
    }, "Пароль может содержать только цифры и латинские буквы");

    $.validator.addMethod("imageResolution", function (value, element) {
        var $image = $('#teacherImage');
        var cropper = $image.data('cropper');
        if (typeof cropper == 'undefined') {
            return false;
        }
        var $cropData = cropper.getData(true);
        if ($cropData['width'] >= 200 || $cropData['height'] >= 200) {
            return true;
        }
        return  false;
    }, "Обрезаемая область должна быть минимум 200x200 пикселей");

    $.validator.addMethod("smallLetters", function (value, element) {
        var regexp = new RegExp("[a-z]");
        return value.match(regexp);
    }, "Пароль должен содержать хотя бы одну строчную английскую букву");

    $.validator.addMethod("bigLetters", function (value, element) {
        var regexp = new RegExp("[A-Z]");
        return value.match(regexp);
    }, "Пароль должен содержать хотя бы одну заглавную латинскую букву");

    $.validator.addMethod("hasNumber", function (value, element) {
        var regexp = new RegExp("[0-9]");
        return value.match(regexp);
    }, "Пароль должен содержать хотя бы одну цифру");

    jQuery.validator.addMethod("filesizeMax", function(value, element, param) {
        var isOptional = this.optional(element),
            file;

        if(isOptional) {
            return isOptional;
        }

        if ($(element).attr("type") === "file") {

            if (element.files && element.files.length) {

                file = element.files[0];
                return ( file.size && file.size <= param );
            }
        }
        return false;
    }, "Вес изображения должен быть меньше 512 Кб");

    $("#login-update-form").validate({
        rules: {
            login: {
                required: true,
                login: true,
                minlength: 3,
                remote: {
                    url: "/verification/login/" + $('#login').attr('user-id'),
                    type: "post",
                }
            },
            password: {
                required: true,
                pwcheck: true,
                minlength: 8
            },
        },
        messages: {
            login: {
                required: "Это поле обязательно для заполнения",
                minlength: "Логин состоит не менее, чем из 3 символов",
                remote: "Этот логин уже занят"
            },
            password: {
                required: "Это поле обязательно для заполнения",
                minlength: "Пароль состоит не менее, чем из 8 символов"
            }
        },
    });

    $("#password-update-form").validate({
        rules: {
            old_password: {
                required: true,
                pwcheck: true,
                minlength: 8
            },
            new_password: {
                required: true,
                pwcheck: true,
                smallLetters: true,
                bigLetters: true,
                hasNumber: true,
                minlength: 8
            },
            new_password_confirmation: {
                required: true,
                pwcheck: true,
                minlength: 8,
                equalTo: "#new_password"
            },
        },
        messages: {
            old_password: {
                required: "Это поле обязательно для заполнения",
                minlength: "Пароль состоит не менее, чем из 8 символов"
            },
            new_password: {
                required: "Это поле обязательно для заполнения",
                minlength: "Пароль состоит не менее, чем из 8 символов"
            },
            new_password_confirmation: {
                required: "Это поле обязательно для заполнения",
                minlength: "Пароль состоит не менее, чем из 8 символов",
                equalTo: "Новый пароль и его подтверждение не совпадают",
            }
        },
    });

    $("#email-update-form").validate({
        rules: {
            email: {
                required: true,
                email: true,
                remote: {
                    url: "/verification/email",
                    type: "post",
                }
            },
            password: {
                required: true,
                minlength: 8,
                pwcheck: true,
            }
        },
        messages: {
            email: {
                required: "Это поле обязательно для заполнения",
                email: "Некорректный email",
                remote: "Этот email уже занят"
            },
            password: {
                required: "Это поле обязательно для заполнения",
                minlength: "Пароль состоит не менее, чем из 8 символов"
            }
        },
    });

    $("#photo-update-form").validate({
        rules: {
            photo: {
                required: true,
                extension: "jpg|jpeg|png",
                filesizeMax: 524288,
                imageResolution: true
            }
        },
        messages: {
            photo: {
                required: "Загрузите фотографию",
            }
        },
    });

    $("#short-description-update-form").validate({
        rules: {
            short_description: {
                required: true,
                rangelength: [10, 138]
            }
        },
        messages: {
            short_description: {
                required: "Это поле обязательно для заполнения",
                rangelength: "Краткое описание должно состоять минимум из 10 символов и максимум из 135"
            }
        },
    });

    $("#full-description-update-form").validate({
        rules: {
            full_description: {
                required: true,
                minlength: 50,
                maxlength: 15000
            }
        },
        messages: {
            full_description: {
                required: "Это поле обязательно для заполнения",
                minlength: "Полное описание должно состоять минимум из 50 символов",
                maxlength: "Полное описание не должно превышать 15000 символов"
            }
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

    // Загрузка фото
    var loadFile = function(event) {

        var $image = $('#teacherImage');
        $image.cropper('destroy');

        if (typeof event.target.files[0] == 'undefined') {
            $image.removeAttr('src');
            $('.custom-file-label').text("Выберите фото");
            $(this).valid();
            return;
        } else {
            $('.custom-file-label[for="photo"]').text(event.target.files[0].name);
        }

        if (!event.target.files[0].name.match(new RegExp("(.jpg|.JPG|.jpeg|.JPEG|.png|.PNG)$"))) {
            $image.removeAttr('src');
            $(this).valid();
            return;
        }

        $image.attr('src', URL.createObjectURL(event.target.files[0]));

        $image.cropper({
            aspectRatio: 1 / 1,
            viewMode: 2,
            modal: false,
            background: false,
            zoomable: true,
            responsive: true
        });

        var cropper = $image.data('cropper');

        $('#photo-update-form').submit(function (event) {
            var $cropData = cropper.getData(true);

            $('#photo_x').val($cropData['x']);
            $('#photo_y').val($cropData['y']);
            $('#photo_width').val($cropData['width']);
            $('#photo_height').val($cropData['height']);
        });

        $image.bind('ready', function () {
            $('#photo').valid();
        });

        $image.bind('cropend', function () {
            $('#photo').valid();
        });
    };

    $('#photo').on('change', loadFile);
});