$("body").on("click","#cookconfirm",function (){
    $.ajax
    ({

        url: "hendlers/cookie_confirm.php", // путь к ajax файлу
        type: "POST",      // тип запроса

        data:
            {
                confirm: 1,
            },


        dataType: "html",
        cache: false,
        // Данные пришли
        success: function(data )
        {
            $('.cookdiv').hide("slow");
            //return document.location.reload(true);

        }
    });
});