<?php
session_start();
require_once 'includs/ip.php';
require_once 'functions/functions.php';
require_once 'includs/check.php';
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta
        name="sputnik-verification"
        content="jjcPO4sqQYWv7K37"
    />
    <script src="https://culturaltracking.ru/static/js/spxl.js" data-pixel-id="1032"></script>
    <meta name="yandex-verification" content="b82701a7766ca759" />
    <meta name="yandex-verification" content="50b98ccfb33aa708" />
    <meta name="keywords" content="Тигран Ахназарян, Южно-Сахалинский камерный оркестр, оркестр">
    <?php
    $p_title = 'Южно-Сахалинский камерный оркестр';
    $ver = random_str(8);
    ?>
    <title><?php echo $p_title;?></title>
    <link rel="icon" href="img/logo/logo.svg" sizes="any" type="image/svg+xml">
    <link href="css/menu.css?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/css/menu.css');?>" rel="stylesheet">
    <link href="css/index.css?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/css/index.css');?>" rel="stylesheet">
    <link href="css/menum.css?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/css/menum.css');?>" rel="stylesheet">
    <link href="css/right_nav.css?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/css/right_nav.css')?>" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="https://code.jquery.com/jquery-latest.js"></script>
</head>

<body>

<?php
//FacebookScript();
include 'includs/links.php';
include 'includs/header.php';
$host = 'https://'.$_SERVER['HTTP_HOST'].'/';
?>

<!--<div class="ubis"><b>XX ЮБИЛЕЙНЫЙ СЕЗОН</b></div>-->
<div class="content">

<div class="eventsarea">
<?php
$query = qwe("
SELECT
anonces.concert_id as ev_id,
anonces.hall_id,
anonces.prog_name,
anonces.sdescr,
anonces.description,
anonces.img,
anonces.topimg,
anonces.aftitle,
anonces.datetime,
anonces.pay,
anonces.age,
anonces.ticket_link,
halls.hall_name,
halls.map
FROM
anonces
INNER JOIN halls ON anonces.hall_id = halls.hall_id
WHERE /*anonces.concert_id > 3 AND*/ datetime >= NOW()
ORDER BY anonces.datetime
");
$prrows = ['','','Вход свободный','Билеты в продаже','Вход по пригласительным','Билеты в продаже'];
foreach($query as $q)
{
ConcertItem($q);
}
if(!$query or mysqli_num_rows($query) == 0)
	VideoItems();
?>
</div>
<div class="eventsarea">
    <?php  NewsCol();?>
</div>
    <?php
    /*
    <div class="vkcom">
    <?php

        FacebookCol();
    ?>

    <br><hr><br>

    <script type="text/javascript" src="https://vk.com/js/api/openapi.js?154"></script>

    <!-- VK Widget -->
    <div id="vk_groups"></div>
    <script type="text/javascript">
    VK.Widgets.Group("vk_groups", {mode: 4, wide: 1, no_cover: 0, height: "800", width: "auto", color1: 'e7ddcb',color3: 'A98700'}, 166038484);
    </script>


</div>
    */
    ?>
</div>
<?php
include 'includs/footer.php';
?>
 
</body>
</html>