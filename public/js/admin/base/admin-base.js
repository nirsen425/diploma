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
        higlightSubmenu();

        highlightLinkByPart('student-applications', '#student-applications');
        highlightLinkByPart('set-limits', '#set-limits');
        highlightLinkByPart('teacher-applications', '#teacher-applications');
    }

    /**
     * Подсвечивает элемент по селектору selector, если
     * внутри этого элемента есть ссылка, значение которе полностью или частично
     * совпадает с partLink
     *
     * @param {string} partLink Часть ссылки или полная ссылка.
     * @param {string} selector Селектор на подсвечиваемый элемент.
     */
    function highlightLinkByPart(partLink, selector) {
        if (window.location.href.match(partLink)) {
            $(selector).addClass('active');
        }
    }
    
    function higlightSubmenu() {
        let currentLink = $('[href="' + window.location.href + '"]');
        let componentsElement = currentLink.closest('.components-element');
        if (currentLink.length != 0 && componentsElement.length != 0) {
            currentLink.addClass('current-link');
            componentsElement.addClass('active');
        }
    }

    highlightCurrentLink();
});