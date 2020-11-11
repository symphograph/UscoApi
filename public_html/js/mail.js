$('body').on('click','#msend',function () {
    //console.log('hhh');
    let name = $('#name').val();
    let email = $('#email').val();
    let msg = $('#msg').val();

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
                msg: msg
            },


        dataType: "html",
        cache: false,
        // Данные пришли
        success: function(data )
        {

        }
    });

});