window.onload = function() {
    getPosters((new Date()).getFullYear(),0);
    };
$("body").on("change","#yearFilter, #sort",function (){
    let year = $("#yearFilter").val();
    let sort = $("#sort").val();
    getPosters(year,sort);
});

function getPosters(year, sort){
    $.ajax
    ({

        url: "hendlers/posters.php", // путь к ajax файлу
        type: "POST",      // тип запроса

        data:
            {
                year: year,
                sort: sort
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