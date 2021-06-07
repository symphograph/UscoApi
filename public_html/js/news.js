window.onload = function() {
    getPosters();
    };
$("body").on("change","#yearFilter, #filter",function (){

    getPosters();
});

function getPosters(){
    let year = $("#yearFilter").val();
    let filter = $("#filter").val();
    $.ajax
    ({

        url: "hendlers/news.php", // путь к ajax файлу
        type: "POST",      // тип запроса

        data:
            {
                year: year,
                filter: filter
            },


        dataType: "html",
        cache: false,
        // Данные пришли
        success: function(data)
        {
            $('#news').html(data);


        }
    });
}