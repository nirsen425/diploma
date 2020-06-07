$(document).ready(function () {
    // Проверка input загрузки файлов на пустоту
    $("#uploadFile").submit(function() {
        var file = $("#files-upload").val();
        if (file == "") {
            $('#notSelected').modal('show');
            return false;
        }
        else {
            return true;
        }
    })
});
