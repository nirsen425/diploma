$(document).ready(function () {
    // Подсчет выбранных для загрузки файлов
    $('#files-upload').on('change', function () {
        var fileCount = $(this.files).length;
        $(this).next('.custom-file-label').html("Файлов выбрано: " + fileCount);
    })
});
