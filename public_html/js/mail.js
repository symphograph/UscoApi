$('body').on('click','#msend',function () {
    //console.log('hhh');
    let name = $('#name').val();
    let email = $('#unused').val();
    let msg = $('#msg').val();
    let email2 = $('#email').val();
    $('#unused').css('border-color','');
    $('#name').css('border-color','');
    $('#msg').css('border-color','');
    $.ajax
    ({

        url: "hendlers/feedbackmail.php", // путь к ajax файлу
        headers: {
            'X-CSRF-TOKEN': $('meta[name="token"]').attr('content')
        },
        type: "POST",      // тип запроса

        data:
            {
                name: name,
                email: email,
                msg: msg,
                email2: email2
            },


        dataType: "html",
        cache: false,
        // Данные пришли
        success: function( resp )
        {
            if(resp === 'ok')
            {
                $('.fidback_area').html('Сообщение отправлено');
                return ;
            }
            if(resp === 'email'){
                $('#unused').css('border-color','red');
            }
            if(resp === 'name'){
                $('#name').css('border-color','red');
            }
            if(resp === 'msg'){
                $('#name').css('border-color','red');
            }
                //return document.location.reload(true);

        }
    });

});