$(function () {
    $(".toggle-login-form").click(function () {
        $('#login-update').slideToggle();
        $('.toggle-login-form').toggleClass('fa-arrow-up').toggleClass('fa-arrow-down');
    });

    $(".toggle-password-form").click(function () {
        $('#password-update').slideToggle();
        $('.toggle-password-form').toggleClass('fa-arrow-up').toggleClass('fa-arrow-down');
    });

    // Валидация
    $.validator.addMethod("login", function (value, element) {
        var regexp = new RegExp("^[a-zA-Z0-9]+$");
        return value.match(regexp);
    }, "Логин может содержать только цифры и латинские буквы");

    $.validator.addMethod("password", function (value, element) {
        var regexp = new RegExp("^[a-zA-Z0-9]+$");
        return value.match(regexp);
    }, "Пароль может содержать только цифры и латинские буквы");

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
                password: true,
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
                password: true,
                minlength: 8
            },
            new_password: {
                required: true,
                password: true,
                smallLetters: true,
                bigLetters: true,
                hasNumber: true,
                minlength: 8
            },
            new_password_confirmation: {
                required: true,
                password: true,
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
});
