$(function () {
    function get_name_browser(){
        var ua = navigator.userAgent;
        if (ua.search(/YaBrowser/) > 0) return 'Яндекс Браузер';
        if (ua.search(/rv:11.0/) > 0) return 'Internet Explorer 11';
        if (ua.search(/MSIE/) > 0) return 'Internet Explorer';
        if (ua.search(/Edge/) > 0) return 'Edge';
        if (ua.search(/Chrome/) > 0) return 'Google Chrome';
        if (ua.search(/Firefox/) > 0) return 'Firefox';
        if (ua.search(/Opera/) > 0) return 'Opera';
        if (ua.search(/Safari/) > 0) return 'Safari';
        return 'Не определен';
    }

    var browser = get_name_browser();
    if (browser == 'Edge' || browser == 'Internet Explorer') {
        $('.shell').width($('.table').width());
    }
});