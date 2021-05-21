window.onload = function() {
    getPosters((new Date()).getFullYear());
    };
$("body").on("change","#yearFilter",function (){
    getPosters($("#yearFilter").val());
});

function getPosters(year){
    $.ajax
    ({

        url: "hendlers/posters.php", // путь к ajax файлу
        type: "POST",      // тип запроса

        data:
            {
                year: year,
            },


        dataType: "html",
        cache: false,
        // Данные пришли
        success: function(data)
        {
            $('#posters').html(data);
            //return document.location.reload(true);

        }
    });
}