$(function () {
    let timestamp = $('.date').text();

    let localDate = new Date(timestamp * 1000)
        .toLocaleString('ru', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: 'numeric',
            minute: 'numeric'
        });

    $('.date').text(localDate);
});