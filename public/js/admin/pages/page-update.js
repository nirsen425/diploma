$(document).ready(function () {

    // Текстовый редактор
    CKEDITOR.replace("content", {
        filebrowserUploadUrl: "/upload/image",
        extraPlugins: 'uploadimage',
        uploadUrl: '/upload/image'
    });

    var description =  CKEDITOR.instances["content"];

    // description.on('change', function () {
    //     CKEDITOR.instances["content"].updateElement();
    //     var validator = $( "#pageUpdate" ).validate();
    //     validator.element("#content");
    // });

    description.on('blur', function () {
        CKEDITOR.instances["content"].updateElement();
        // var validator = $( "#pageUpdate" ).validate();
        // validator.element("#content");
        $('#full_description').blur();
    });

    $('#pageUpdate').submit(function () {
        CKEDITOR.instances["content"].updateElement();
    });

    $.validator.setDefaults({
        // при этой настройке никакие скрытые поля не будут игнорироваться,
        // например текстовая область на которую установлен ckEditor(она скрывается ckEditor'ом)
        ignore: [],
    });

    $("#pageUpdate").validate({
        rules: {
            title: {
                required: true,
                minlength: 3,
                maxlength: 20,
                remote: {
                    url: "/verification/title/" + $('#title').attr('page-id'),
                    type: "post",
                }
            },
            content: {
                required: true,
                minlength: 50,
            },
            meta_headline: {
                required: true
            },
            meta_description: {
                required: true
            },
            meta_words: {
                required: true
            }
        },
        messages: {
            title: {
                required: "Это поле обязательно для заполнения",
                minlength: "Пожалуйста, введите не менее 3 символов",
                maxlength: "Заголовок не должен превышать 20 символов",
                remote: "Такой заголовок уже существует"
            },
            content: {
                required: "Это поле обязательно для заполнения",
                minlength: "Полное описание должно состоять минимиум из 50 символов"
            },
            meta_headline: {
                required: "Это поле обязательно для заполнения",
                minlength: "Пожалуйста, введите не менее 8 символов"
            },
            meta_description: {
                required: "Это поле обязательно для заполнения"
            },
            meta_words: {
                required: "Это поле обязательно для заполнения"
            }
        },
        // Определение места вставки сообщения об ошибке
        errorPlacement: function(error, element) {
            if (element.attr("name") == "content") {
                error.insertAfter("#content");
            } else {
                error.insertAfter(element);
            }
        }
    });
});