<?php
//
$root = $_SERVER['DOCUMENT_ROOT'];
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/check.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config2.php';
if(!$myip) die();

$pers_id = $_GET['pers_id'] ?? 0;
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">


    <meta name="keywords" content="Тигран Ахназарян, Южно-Сахалинский камерный оркестр, оркестр">
    <?php
    $p_title = 'Южно-Сахалинский камерный оркестр';
    $ver = random_str(8);
    ?>
    <title><?php echo $p_title;?></title>
    <link rel="icon" href="img/logo/logo.svg" sizes="any" type="image/svg+xml">
    <link href="css/menu.css?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/css/menu.css');?>" rel="stylesheet">
    <link href="css/index.css?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/css/index.css');?>" rel="stylesheet">
    <link href="css/afisha.css?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/css/afisha.css');?>" rel="stylesheet">
    <link href="css/menum.css?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/css/menum.css');?>" rel="stylesheet">
    <link href="css/right_nav.css?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/css/right_nav.css')?>" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="https://code.jquery.com/jquery-latest.js"></script>
    <link rel="stylesheet" href="css/croppie.css?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/css/right_nav.css')?>" />
<!--    <script src="https://cdn.jsdelivr.net/npm/exif-js"></script>-->
    <script src="js/Croppie-2.6.5/croppie.js"></script>
</head>

<body>

<?php
//FacebookScript();
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/links.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/header.php';
$host = 'https://'.$_SERVER['SERVER_NAME'].'/';
?>

<div class="content">
    <div class="eventsarea">

        <div id="buttons">
            <?php pers_select2($pers_id);?>
            <div class="btn_area">
                <div class="bybtn" id="upload"><div class="bybtntxt">Загрузить</div></div>
            </div>
            <input id="c_input24" name="file" type="file" style="display: none"/>
            <input name="photo_c" type="hidden" style="display: none" value="">
            <input name="photo_i" type="hidden" style="display: none" value="">
        </div>
        <div style="height: 160px">
            <div class="crop_area"><img class="profile_photo_i" src=""></div>
        </div>

        <div class="btn_area">
            <div class="bybtn" id="save" style="display: none"><div class="bybtntxt">Сохранить</div></div>
        </div>

        <img id="result_photo" src="" style="display: none">

        <div class="btn_area">
            <div class="bybtn" id="refresh" style="display: none"><div class="bybtntxt">Заново</div></div>
        </div>


    </div>

    <div class="eventsarea">

    </div>
</div>
<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/footer.php';
?>
 
</body>
<script>

    $('#buttons').on('click','#upload',function()
    {
        $("#c_input24").trigger('click');
        return false;

    });

    $("#c_input24").on('change',function() {

        var formData = new FormData();
        var profile = $('.profile_photo_i');
        formData.append('file', $(this)[0].files[0]);
        formData.append('pers_id',$('#pers_select').val());

        $.ajax({
            url: 'hendlers/get_original.php',
            headers: {
                //headers
            },
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            cache: false,
            success: function(data){

                $('input[name="photo_c"]').val(data.file_max);
                setTimeout(function() {$('#upload').hide('slow');}, 1000);
                $('#save').show();

                profile.attr('src',data.path_max);
                basic = profile.croppie({
                    viewport: {
                        width: 100,
                        height: 100,
                        type: 'circle'
                    }
                })
            }
        })
    })

    $('#save').on('click',function(){

        basic.croppie('result', 'base64').then(function (html){
            var query = {
                photo: html,
                photo_c: $('input[name="photo_c"]').val(),
                pers_id: $('#pers_select').val()

            }

            SendCroped(query);

        });
    })

    function SendCroped(query){
        //console.log(query);


        $.ajax({
            url: 'hendlers/get_crop.php',
            headers: {
                //headers
            },
            type: "POST",
            data: query,
            //processData: false,
            // contentType: false,
            dataType: "html",
            cache: false,
            success: function(data){
                //console.log("hgfhg");
                //$('input[name="photo_i"]').val(data);

                $('#result_photo').attr('src','img/avatars/small/'+data);
                $('#result_photo').show();
                setTimeout(function() {$('#save').hide('slow');}, 300);
                setTimeout(function() {$('#refresh').show('slow');}, 600);

            }

        })
    }

    $('#refresh').on('click',function(){

        return document.location.reload(true);

    });




</script>
</html>