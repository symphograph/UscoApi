<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
$User = User::byCheck();
$host = 'https://'.$_SERVER['SERVER_NAME'].'/';
$evid = intval($_GET['evid'] ?? 0);
$ptypes = ['','','Вход свободный','Онлайн продажа завершена','Вход по пригласительным'];
$Anonce = Anonce::getReady($evid)
//printr($Anonce);
?>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title><?php echo $Anonce->prog_name?></title>
    <link rel="icon" href="img/logo/logo.svg" sizes="any" type="image/svg+xml">
    <?php CssMeta(['menu.css','index.css','event.css','menum.css', 'right_nav.css'])?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/links.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/header.php';
?>
<div class="content">
    <div class="eventsarea">
        <?php
            echo $Anonce->getHtml();
        ?>
    </div>
</div>
<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/footer.php';
?>
</body>
</html>