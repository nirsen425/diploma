$(document).ready(function () {
    $('#overlay').on('click', function () {
        $('aside').toggleClass('toggler');
        $('#overlay').toggleClass('toggler');
        $('main').toggleClass('toggler');
    });

    $('#switchSidebar, #hideSidebar').on('click', function () {
        $('aside').toggleClass('toggler');
        $('#overlay').toggleClass('toggler');
        $('main').toggleClass('toggler');
    });

    function highlightCurrentLink() {
        var currentLink = $('[href="' + window.location.href + '"]');
        currentLink.addClass('current-link');
        currentLink.closest('.components-element').addClass('active');
    }

    highlightCurrentLink();
});