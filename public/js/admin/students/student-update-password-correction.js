$('#changePassword').click(function () {
    var password = $('#password');
    password.toggleClass('d-none');

    if (password.hasClass('d-none')) {
        password.attr('aria-invalid', false);
        password.val('');
        password.siblings('.error').remove();
    }
});