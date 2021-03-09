<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/check.php';
?>
<!doctype html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta name="proculture-verification" content="9974889fb39244589ef78eb3c3879433" />
    <meta name="keywords" content="Тигран Ахназарян, Южно-Сахалинский камерный оркестр, оркестр">
    <?php
    $p_title = 'Южно-Сахалинский камерный оркестр';
    $ver = random_str(8);
    ?>
    <title><?php echo $p_title;?></title>
    <link rel="icon" href="img/logo/logo.svg" sizes="any" type="image/svg+xml">
    <?php CssMeta(['menu.css','index.css','afisha.css','menum.css', 'right_nav.css'])?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/links.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/header.php';
$host = 'https://'.$_SERVER['SERVER_NAME'].'/';
?>

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

//$prrows = ['','','Вход свободный','Билеты в продаже','Вход по пригласительным','Билеты в продаже'];
?>
    <div class="evcols">
        <?php
            foreach($query as $q) {
                ConcertItem($q);
            }
        ?>
    </div>
</div>
<div class="eventsarea">
    <?php
    $qwe = qwe( "SELECT * FROM video ORDER BY v_date DESC LIMIT 6");
    VideoItems($qwe);
    ?>

</div>
    <div class="tisers">
        <div class="opros">
            <a href="https://clck.ru/RautH" target="_blank">
                Пройти опрос
            </a>
            <br>
            по независимой оценке качества условий оказания услуг учреждениями культуры<br><br>
        </div>

        <a href="http://polerusskoe.ru/" target="_blank"
           style="color: white; font-size: 20px; text-decoration: none;">
            <div class="opros rupole" >
                <p style="font-family: 'Rubik Mono One',serif;">Русское поле</p>
                <br>

                Фестиваль<br>славянского<br>искусства<br>
            </div>
        </a>

    </div>
    <div class="eventsarea">
        <?php  NewsCol();?>
        <a href="news.php">К другим новостям</a>
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
include ROOT.'/includs/footer.php';
?>
 
</body>
</html>