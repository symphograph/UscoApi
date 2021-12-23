<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/check.php';
?>
<!doctype html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta name="proculture-verification" content="9974889fb39244589ef78eb3c3879433" />
    <meta
        name="sputnik-verification"
        content="jjcPO4sqQYWv7K37"
    />
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
WHERE  datetime >= NOW()
ORDER BY anonces.datetime
");
$qwe = $query->fetchAll(PDO::FETCH_CLASS,"Anonce");
?>
    <div class="evcols">
        <?php
            foreach($qwe as $q) {

                $Anonce = new AnonceCard();
                $Anonce->clone($q);
                $Anonce->printItem();
            }

        ?>
    </div>
</div>
    <div class="eventsarea" id="banner">
        <?php
        $NewItem = new NewsItem(84);
        echo $NewItem->PajeItem();
        ?>
        <br><hr><br><br>
    </div>

<!--    <div class="eventsarea">
        <a href="news.php?filter=3" style="text-decoration: none">
            <div style="background-color: #fbf5db">
                <img src="img/logo/euterpe.svg" alt="ttt" style="width: 100%">
                <div style="
                    color: #2d3a63; padding: 1em;
                    text-align: center;
                    font-family: GoudyTrajan,serif;">
                    IV Дальневосточный музыкальный фестиваль для детей и юношества
                    <br><br><br>
                </div>
            </div>
        </a><br><hr><br>
    </div>-->

    <div class="eventsarea">
        <?php
        $qwe = qwe( "SELECT * FROM video ORDER BY v_date DESC LIMIT 6");
        VideoItems($qwe);
        ?>
    </div>
    <div class="eventsarea"><br><hr><br><br>
        <div class="teasers">
            <div class="nimg_block">
                    <a href="https://clck.ru/RautH" target="_blank">
                        <img src="img/tisers/opros.svg">
                    </a>
            </div>
            <div class="nimg_block">
                    <a href="news.php?filter=3">
                        <img src="img/afisha/topp_euterpe_2021.svg">
                    </a>
            </div>
            <div class="nimg_block">
                    <a href="new.php?new_id=76">
                        <img src="img/news/pw/76.jpg?ver=bf5237f077839489cd089d15011e1a1f">
                    </a>
            </div>
        </div>
    </div>
    <div class="eventsarea">
        <div class="newscol"><br>
            <div class="ntitle"><b>Новости оркестра</b></div><br><hr><br>
            <?php  NewsCol();?>
            <a href="news.php">К другим новостям</a>
        </div>
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
include dirname($_SERVER['DOCUMENT_ROOT']).'/includs/footer.php';
?>
</body>
</html>