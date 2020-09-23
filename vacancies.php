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
    <link href="css/afisha.css?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/css/afisha.css');?>" rel="stylesheet">
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

<div class="content">

<div class="eventsarea">
Data is empty.
</div>
<?php
include 'includs/footer.php';
?>
 
</body>
</html>