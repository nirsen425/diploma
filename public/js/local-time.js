$(function () {
    $('.date').each(function (i, elem) {
        let timestamp = $(this).text();

        let localDate = new Date(timestamp * 1000)
            .toLocaleString('ru', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: 'numeric',
                minute: 'numeric'
            });

        $(this).text(localDate);
    });
});