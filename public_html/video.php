<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once ROOT.'/includs/check.php';


$p_title = 'Южно-Сахалинский камерный оркестр';
$ver = random_str(8);

?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title><?php echo $p_title;?></title>
    <link rel="icon" href="img/logo/logo.svg" sizes="any" type="image/svg+xml">
    <?php CssMeta(['menu.css','index.css','menum.css', 'right_nav.css'])?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
<?php
require_once ROOT.'/includs/links.php';
require_once ROOT.'/includs/header.php';
$host = 'https://'.$_SERVER['SERVER_NAME'].'/';
?>

<div class="content">
    <div class="eventsarea">
        <?php
        VideoItems();
        ?>
    </div>
</div>
<?php
require_once ROOT.'/includs/footer.php';
?>
 
</body>
</html>