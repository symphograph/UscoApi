<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root.'/../includs/check.php';
?>
<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">
<?php
$p_title = 'Основные сведения';
$ver = random_str(8);
?>
<title><?php echo $p_title;?></title>
    <link rel="icon" href="img/logo/logo.svg" sizes="any" type="image/svg+xml">
<link href="css/menu.css?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/css/menu.css');?>" rel="stylesheet">
<link href="css/index.css?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/css/index.css');?>" rel="stylesheet">
<link href="css/menum.css?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/css/menum.css');?>" rel="stylesheet">
<link href="css/right_nav.css?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/css/right_nav.css');?>" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script type="text/javascript" src="https://code.jquery.com/jquery-latest.js"></script>
</head>

<body>
<?php
require_once $root.'/../includs/links.php';
require_once $root.'/../includs/header.php';
?>


<div class="content">
    <div class="text">
        <div class="p_title"><?php echo $p_title;?></div><br>
        <p>Полное название:</p>
        МУНИЦИПАЛЬНОЕ БЮДЖЕТНОЕ УЧРЕЖДЕНИЕ<br>"ЮЖНО-САХАЛИНСКИЙ КАМЕРНЫЙ ОРКЕСТР"<br><br>
        <p>Сокращенное название:</p>
        МБУ "ЮСКО"<br><br>
        Дата постановки на учет:<br>
        05.01.2001<br><br>
        <br>
        Учредитель: <br>
        <a href="https://culture.yuzhno-sakh.ru/">Департамент культуры и туризма г.Южно-Сахалинск</a>
        <br><br>

        <a href="documents.php">Документы</a><br>

        <br>
    </div>
</div>
<?php
require_once $root.'/../includs/footer.php';
?>
</body>
</html>