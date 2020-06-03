$(function () {

    // Изменение направления "практики"
    $('select[name="direction-practice"]').change(function () {
        let practiceData = {
            directionId: $(this).find('option:selected').val(),
            courseId: $('select[name="course-practice"]').find('option:selected').val(),
        };

        $.ajax({
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType : "json",
            data: {'practiceData': practiceData},
            url: "/lecturer/cabinet/practice",
            success: function(dataPractice) {
                if (dataPractice) {
                    $("#application-start").html(dataPractice['application_start']);
                    $("#application-end").html(dataPractice['application_end']);
                    $("#practice-start").html(dataPractice['practice_start']);
                    $("#practice-end").html(dataPractice['practice_end']);
                    $("#practice-info-text").html(dataPractice['practice_info']);
                }
            }
        });
    });

    // Изменение курса "практики"
    $('select[name="course-practice"]').change(function () {
        let practiceData = {
            directionId: $('select[name="direction-practice"]').find('option:selected').val(),
            courseId: $(this).find('option:selected').val(),
        };

        $.ajax({
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType : "json",
            data: {'practiceData': practiceData},
            url: "/lecturer/cabinet/practice",
            success: function(dataPractice) {
                if (dataPractice) {
                    $("#application-start").html(dataPractice['application_start']);
                    $("#application-end").html(dataPractice['application_end']);
                    $("#practice-start").html(dataPractice['practice_start']);
                    $("#practice-end").html(dataPractice['practice_end']);
                    $("#practice-info-text").html(dataPractice['practice_info']);
                }
            }
        });
    });

    // Изменение направления "файлы"
    $('select[name="direction-files"]').change(function () {
        let filesData = {
            directionId: $(this).find('option:selected').val(),
            courseId: $('select[name="course-files"]').find('option:selected').val(),
        };

        $.ajax({
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType : "json",
            data: {'filesData': filesData},
            url: "/lecturer/cabinet/files",
            success: function(dataFiles) {
                if (!(dataFiles === false)) {
                    $("#not-files").replaceWith('<div class="pt-3 pb-3" id="not-files">\n' +
                        '                           <table class="table bg-light table-hover" id="files-table">\n' +
                        '                               <thead class="thead-dark">\n' +
                        '                                   <tr>\n' +
                        '                                       <th class="d-none">id</th>\n' +
                        '                                       <th scope="col" class="pl-8">Название файла</th>\n' +
                        '                                       <th scope="col" class="pl-8">Дата загрузки</th>\n' +
                        '                                       <th scope="col" class="pl-8">Действия</th>\n' +
                        '                                   </tr>\n' +
                        '                               </thead>\n' +
                        '                               <tbody id="files-place">\n' +
                        '                               </tbody>\n' +
                        '                           </table>\n' +
                        '                        </div>')

                    $("#files-place tr").remove();
                    for(let i = 0; i < dataFiles['filesCount']; i++) {
                        $("#files-place").append('<tr>' +
                            '                       <td class="d-none file-id">' + dataFiles[i]['fileId'] + '</td>\n' +
                            '                       <td class="align-bottom p-8">' + dataFiles[i]['fileFullName'] + '</td>\n' +
                            '                       <td class="align-bottom p-8 date">' + dataFiles[i]['created_at'] + '</td>\n' +
                            '                       <td>\n' +
                            '                           <a href="' + dataFiles[i]['urlFile'] + '" class="download">\n' +
                            '                               <i class="fa fa-download"></i>\n' +
                            '                           </a>\n' +
                            '                       </td>'+
                            '                     </tr>')
                    }
                }
                else
                {
                    $("#files-table").replaceWith('<div class="pt-3 pl-2" id="not-files">Нет загруженных файлов</div>');
                }
            },
        });
    });

    // Изменения курса "файлы"
    $('select[name="course-files"]').change(function () {
        let filesData = {
            directionId: $('select[name="direction-files"]').find('option:selected').val(),
            courseId: $(this).find('option:selected').val(),
        };

        $.ajax({
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType : "json",
            data: {'filesData': filesData},
            url: "/lecturer/cabinet/files",
            success: function(dataFiles) {
                if (!(dataFiles === false)) {
                    $("#not-files").replaceWith('<div class="pt-3 pb-3" id="not-files">\n' +
                        '                           <table class="table bg-light table-hover" id="files-table">\n' +
                        '                               <thead class="thead-dark">\n' +
                        '                                   <tr>\n' +
                        '                                       <th class="d-none">id</th>\n' +
                        '                                       <th scope="col" class="pl-8">Название файла</th>\n' +
                        '                                       <th scope="col" class="pl-8">Дата загрузки</th>\n' +
                        '                                       <th scope="col" class="pl-8">Действия</th>\n' +
                        '                                   </tr>\n' +
                        '                               </thead>\n' +
                        '                               <tbody id="files-place">\n' +
                        '                               </tbody>\n' +
                        '                           </table>\n' +
                        '                        </div>')

                    $("#files-place tr").remove();
                    for(let i = 0; i < dataFiles['filesCount']; i++) {
                        $("#files-place").append('<tr>' +
                            '                       <td class="d-none file-id">' + dataFiles[i]['fileId'] + '</td>\n' +
                            '                       <td class="align-bottom p-8">' + dataFiles[i]['fileFullName'] + '</td>\n' +
                            '                       <td class="align-bottom p-8 date">' + dataFiles[i]['created_at'] + '</td>\n' +
                            '                       <td>\n' +
                            '                           <a href="' + dataFiles[i]['urlFile'] + '" class="download">\n' +
                            '                               <i class="fa fa-download"></i>\n' +
                            '                           </a>\n' +
                            '                       </td>'+
                            '                     </tr>')
                    }
                }
                else
                {
                    $("#files-table").replaceWith('<div class="pt-3 pl-2" id="not-files">Нет загруженных файлов</div>');
                }
            },
        });
    });

    $(".toggle-login-form").click(function () {
        $('#login-update').slideToggle();
        $(this).toggleClass('fa-arrow-up').toggleClass('fa-arrow-down');
    });

    $(".toggle-password-form").click(function () {
        $('#password-update').slideToggle();
        $(this).toggleClass('fa-arrow-up').toggleClass('fa-arrow-down');
    });

    $(".toggle-photo-form").click(function () {
        $('#photo-update').slideToggle();
        $(this).toggleClass('fa-arrow-up').toggleClass('fa-arrow-down');
    });

    $(".toggle-short-description-form").click(function () {
        $('#short-description-update').slideToggle();
        $(this).toggleClass('fa-arrow-up').toggleClass('fa-arrow-down');
    });

    $(".toggle-full-description-form").click(function () {
        $('#full-description-update').slideToggle();
        $(this).toggleClass('fa-arrow-up').toggleClass('fa-arrow-down');
    });


    // Текстовый редактор
    CKEDITOR.replace("full_description", {
        filebrowserUploadUrl: "/upload/image",
        extraPlugins: 'uploadimage',
        uploadUrl: '/upload/image'
    });

    var description =  CKEDITOR.instances["full_description"];

    description.on('change', function () {
        CKEDITOR.instances["full_description"].updateElement();
    });

    description.on('blur', function () {
        CKEDITOR.instances["full_description"].updateElement();
        $('#full_description').blur();
    });

    // Валидация
    $.validator.setDefaults({
        // при этой настройке никакие скрытые поля не будут игнорироваться,
        // например текстовая область на которую установлен ckEditor(она скрывается ckEditor'ом)
        ignore: [],
    });

    $.validator.addMethod("login", function (value, element) {
        var regexp = new RegExp("^[a-zA-Z0-9]+$");
        return value.match(regexp);
    }, "Логин может содержать только цифры и латинские буквы");

    $.validator.addMethod("password", function (value, element) {
        var regexp = new RegExp("^[a-zA-Z0-9]+$");
        return value.match(regexp);
    }, "Пароль может содержать только цифры и латинские буквы");

    $.validator.addMethod("imageResolution", function (value, element) {
        var $image = $('#teacherImage');
        var cropper = $image.data('cropper');
        var $cropData = cropper.getData(true);
        if ($cropData['width'] >= 200 || $cropData['height'] >= 200) {
            return true;
        }
        return  false;
    }, "Обрезаемая область должна быть минимум 200x200 пикселей");

    $.validator.addMethod("smallLetters", function (value, element) {
        var regexp = new RegExp("[a-z]");
        return value.match(regexp);
    }, "Пароль должен содержать хотя бы одну строчную английскую букву");

    $.validator.addMethod("bigLetters", function (value, element) {
        var regexp = new RegExp("[A-Z]");
        return value.match(regexp);
    }, "Пароль должен содержать хотя бы одну заглавную латинскую букву");

    $.validator.addMethod("hasNumber", function (value, element) {
        var regexp = new RegExp("[0-9]");
        return value.match(regexp);
    }, "Пароль должен содержать хотя бы одну цифру");

    $("#login-update-form").validate({
        rules: {
            login: {
                required: true,
                login: true,
                minlength: 3,
                remote: {
                    url: "/verification/login/" + $('#login').attr('user-id'),
                    type: "post",
                }
            },
            password: {
                required: true,
                password: true,
                minlength: 8
            },
        },
        messages: {
            login: {
                required: "Это поле обязательно для заполнения",
                minlength: "Логин состоит не менее, чем из 3 символов",
                remote: "Этот логин уже занят"
            },
            password: {
                required: "Это поле обязательно для заполнения",
                minlength: "Пароль состоит не менее, чем из 8 символов"
            }
        },
    });

    $("#password-update-form").validate({
        rules: {
            old_password: {
                required: true,
                password: true,
                minlength: 8
            },
            new_password: {
                required: true,
                password: true,
                smallLetters: true,
                bigLetters: true,
                hasNumber: true,
                minlength: 8
            },
            new_password_confirmation: {
                required: true,
                password: true,
                minlength: 8,
                equalTo: "#new_password"
            },
        },
        messages: {
            old_password: {
                required: "Это поле обязательно для заполнения",
                minlength: "Пароль состоит не менее, чем из 8 символов"
            },
            new_password: {
                required: "Это поле обязательно для заполнения",
                minlength: "Пароль состоит не менее, чем из 8 символов"
            },
            new_password_confirmation: {
                required: "Это поле обязательно для заполнения",
                minlength: "Пароль состоит не менее, чем из 8 символов",
                equalTo: "Новый пароль и его подтверждение не совпадают",
            }
        },
    });

    $("#photo-update-form").validate({
        rules: {
            photo: {
                required: true,
                imageResolution: true
            }
        },
        messages: {
            photo: {
                required: "Загрузите фотографию"
            }
        },
    });

    $("#short-description-update-form").validate({
        rules: {
            short_description: {
                required: true,
                rangelength: [10, 100]
            }
        },
        messages: {
            short_description: {
                required: "Это поле обязательно для заполнения",
                rangelength: "Краткое описание должно состоять минимум из 10 символов и максимум из 100"
            }
        },
    });

    $("#full-description-update-form").validate({
        rules: {
            full_description: {
                required: true,
                minlength: 50
            }
        },
        messages: {
            full_description: {
                required: "Это поле обязательно для заполнения",
                minlength: "Полное описание должно состоять минимум из 50 символов"
            }
        },
        // Определение места вставки сообщения об ошибке
        errorPlacement: function(error, element) {
            if (element.attr("name") == "full_description") {
                error.insertAfter("#cke_full_description");
            } else {
                error.insertAfter(element);
            }
        }
    });

    // Загрузка фото
    var loadFile = function(event) {
        var teacherImage = document.getElementById('teacherImage');
        teacherImage.src = URL.createObjectURL(event.target.files[0]);

        var $image = $('#teacherImage');
        $image.cropper('destroy');

        $image.cropper({
            aspectRatio: 1 / 1,
            viewMode: 2,
            modal: false,
            background: false,
            zoomable: true,
            responsive: true
        });

        var cropper = $image.data('cropper');

        $('#photo-update-form').submit(function (event) {
            var $cropData = cropper.getData(true);

            $('#photo_x').val($cropData['x']);
            $('#photo_y').val($cropData['y']);
            $('#photo_width').val($cropData['width']);
            $('#photo_height').val($cropData['height']);
        });
    };

    $('#photo').on('change', loadFile);
});
