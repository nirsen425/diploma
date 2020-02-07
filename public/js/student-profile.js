$(function () {
    $(".toggle-login-form").click(function () {
        $('#login-update').slideToggle();
        $('.toggle-login-form').toggleClass('fa-arrow-up').toggleClass('fa-arrow-down');
    });

    $(".toggle-password-form").click(function () {
        $('#password-update').slideToggle();
        $('.toggle-password-form').toggleClass('fa-arrow-up').toggleClass('fa-arrow-down');
    });
});
