$(function () {
    // $('input[name="year"]').on('keyup', function(){
    //     $(this).val($(this).val().replace (/\D/g, ''));
    // });

    $('input[name="new-year"]').keypress(function () {
        if (event.keyCode < 48 || event.keyCode > 57)
            event.returnValue= false;
    });

    $('#add-new-year').click(function () {
        let newYear = $('input[name="new-year"]').val();
        let yearList = $('select[name="year"]');
        let optionList = yearList.find('option');
        let yearExist = false;

        optionList.each(function () {
            if ($(this).text() == newYear) {
                yearExist = true;
            }
        });

        if (yearExist) {
            $('#year-exist').modal('show');
            return;
        }

        yearList.append('<option selected>' + newYear + '</option>');
        yearList.change();
    });

    $('select[name="year"]').change(function () {
        location.href= "/admin/set-limits/" + $(this).find('option:selected').text();
    });

    $('.limit').keypress(function () {
        if (event.keyCode < 48 || event.keyCode > 57)
            event.returnValue= false;
    });

   $('#select-all-teachers').click(function () {
       if ($(this).hasClass('active')) {
           $('input[name="change"]').prop('checked', false);
       } else {
           $('input[name="change"]').prop('checked', true);
       }

       $(this).toggleClass('active');
   });

   $('.all-course-selector').click(function () {
       let cell = $(this).closest('td');
       if ($(this).hasClass('active')) {
           cell.find('.course-selector').prop('checked', false);
       } else {
           cell.find('.course-selector').prop('checked', true);
       }

       $(this).toggleClass('active');
   });

   $('#change-limits').click(function () {
       // if (!$('select[name="year"]').text()) {
       //     $('#emptyYear').modal('show');
       //     return;
       // }
       let year = $('select[name="year"] option:selected').text();
       let teacherLimitsArray = new Array();

       $('#teacher-limits-table tr').each(function(row){
           if ($(this).find('td:nth-child(2)').find('input').prop('checked')) {
               let teacherLimitObject = new Object();
               $(this).find('td').each(function(cell){
                   if (cell == 0) {
                       teacherLimitObject['teacher_id'] = $(this).text();
                   }
                   if (cell == 3) {
                       if ($(this).find('.limit').val() == "") {
                           $('#emptyLimit').modal('show');
                           return false;
                       }
                       teacherLimitObject['limit'] = $(this).find('.limit').val();
                   }
                   if (cell == 4) {
                       teacherLimitObject['first_course'] = $(this).find('input[name="first_course"]').prop('checked') ? 1 : 0;
                       teacherLimitObject['second_course'] = $(this).find('input[name="second_course"]').prop('checked') ? 1 : 0;
                       teacherLimitObject['third_course'] = $(this).find('input[name="third_course"]').prop('checked') ? 1 : 0;
                       teacherLimitObject['fourth_course'] = $(this).find('input[name="fourth_course"]').prop('checked') ? 1 : 0;
                   }
               });
               teacherLimitObject['year'] = year;
               teacherLimitsArray.push(teacherLimitObject);
           }
       });
       if (teacherLimitsArray.length == 0) {
           $('#notSelectTeacher').modal('show');
       }
       // console.log(teacherLimitsArray);

       // let teacherLimitArrayJson = JSON.stringify(teacherLimitsArray);

       // Отправляем Json с преподавателями и их ограниченимями на сервер

       $.ajax({
           type: 'post',
           headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           },
           data: {teacherLimitsArray: teacherLimitsArray},
           url: "/admin/set-limits",
           success: function(status) {
               if (status) {
                   $('#change-success').modal('show');
               }
           }
       });

       // $.post("/admin/set-applications", { teacherLimitArray: teacherLimitsArray}, )

   });

});