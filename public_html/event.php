<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/check.php';
$host = 'https://'.$_SERVER['SERVER_NAME'].'/';
$evid = intval($_GET['evid']);
$ptypes = ['','','Вход свободный','Онлайн продажа завершена','Вход по пригласительным'];
$Anonce = new AnoncePaje($evid);

?>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title><?php echo $Anonce->prog_name?></title>
    <link rel="icon" href="img/logo/logo.svg" sizes="any" type="image/svg+xml">
    <?php CssMeta(['menu.css','index.css','event.css','menum.css', 'right_nav.css'])?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- You can use Open Graph tags to customize link previews.
    Learn more: https://developers.facebook.com/docs/sharing/webmasters -->
    <meta property="og:url"           content="<?php echo $host.'event.php?evid='.$Anonce->ev_id?>" />
    <meta property="og:type"          content="website" />
    <meta property="og:title"         content="<?php echo strip_tags($Anonce->prog_name)?>" />
    <meta property="og:description"   content="<?php echo $Anonce->fDate().' '.$Anonce->fTime()?>" />
    <meta property="og:image"         content="<?php echo $host.$Anonce->Poster->file;?>" />
    <meta property="og:image:width"   content="<?php echo $Anonce->Poster->width?>" />
    <meta property="og:image:height"  content="<?php echo $Anonce->Poster->height?>" />
</head>
<body>
<?php
FacebookScript();
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