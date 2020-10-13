<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root.'/includs/ip.php';
require_once $root.'/functions/functions.php';
require_once $root.'/includs/check.php';
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="keywords" content="Тигран Ахназарян, Южно-Сахалинский камерный оркестр, оркестр">
    <?php
    $p_title = 'Вакансии';
    $ver = random_str(8);
    ?>
    <title><?php echo $p_title;?></title>
    <link rel="icon" href="img/logo/logo.svg" sizes="any" type="image/svg+xml">
    <link href="css/menu.css?ver=<?php echo md5_file($root.'/css/menu.css');?>" rel="stylesheet">
    <link href="css/index.css?ver=<?php echo md5_file($root.'/css/index.css');?>" rel="stylesheet">
    <link href="css/afisha.css?ver=<?php echo md5_file($root.'/css/afisha.css');?>" rel="stylesheet">
    <link href="css/menum.css?ver=<?php echo md5_file($root.'/css/menum.css');?>" rel="stylesheet">
    <link href="css/right_nav.css?ver=<?php echo md5_file($root.'/css/right_nav.css')?>" rel="stylesheet">
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

<div class="content">

<div class="eventsarea">
    <div class="text">
    <p>ЮЖНО-САХАЛИНСКИЙ КАМЕРНЫЙ ОРКЕСТР</p>
    <p>(Художественный руководитель и главный дирижер ТИГРАН АХНАЗАРЯН)</p>

    объявляет конкурс на замещение вакантных должностей артистов оркестра:
    <br><br>
    <em>Группа струнных инструментов:</em>

    <li>1 альт (место в группе)</li>
    <li>1 контрабас (концертмейстер группы)</li>
    <li>1 арфа</li>

    <br><br>
    <em>Группа деревянных духовых инструментов:</em>
    <li>1-й гобой (концертмейстер группы)</li>
    <li>2-й гобой (регулятор группы)</li>
    <li>1-й фагот (концертмейстер группы)</li>
    <br><br>
    <em>Группа медных духовых инструментов:</em>
    <li>2-я валторна (регулятор группы) </li>
    <li>3-я труба (регулятор группы) </li>
    <br><br>
    <em>Группа ударных инструментов:</em>
    <li>1 (концертмейстер группы) </li>
    <br><br>
    <li>Заработная плата от 60.000р. (по результатам собеседования и прослушивания).</li>
    <li>Арендное жилье (оплата найма компенсируется).</li>
    <li>Полный соц. пакет, включая оплату проезда к месту отдыха раз в два года.</li>
    <li>Различные социальные выплаты и премии.</li>
    <li>Возможность сольных выступлений с оркестром, дополнительной преподавательской деятельности.</li>
    <li>Международная гастрольная деятельность.</li>
    <li>Интересная творческая работа в молодом, развивающемся коллективе.</li>
    <br><br>
    Резюме и видеозаписи отправлять по эл. почте: <a href="mailto:erazhisht@gmail.com">erazhisht@gmail.com</a><br>
    или на моб.: <a href="tel:+79841341238">+7-984-134-12-38</a> (WhatsApp)
    </div>
</div>
<?php
include 'includs/footer.php';
?>
 
</body>
</html>