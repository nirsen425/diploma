$(document).ready(function () {
    $("#uploadFile").submit(function() {
        var file = $("#files").val();
        if (file == "") {
            $('#notSelected').modal('show');
            return false;
        }
        else {
            return true;
        }
    })
});
