$(document).ready(function () {
    $('#files').on('change', function () {
        var fileCount = $(this.files).length;
        $(this).next('.custom-file-label').html("Файлов выбрано: " + fileCount);
    })
});
