<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/check.php';

$p_title = 'Афиши';
$ver = random_str(8);
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title><?php echo $p_title;?></title>
    <link rel="icon" href="img/logo/logo.svg" sizes="any" type="image/svg+xml">
    <?php CssMeta(['menu.css','index.css','afisha.css','menum.css', 'right_nav.css'])?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="https://code.jquery.com/jquery-latest.js"></script>

</head>

<body>


<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/links.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/header.php';
$host = 'https://'.$_SERVER['SERVER_NAME'].'/';
?>

<div class="content">

<div class="eventsarea">
<div class="p_title">

    <?php echo $p_title;?>

    <select name="year" id="yearFilter">
        <?php
            for(!$i = date('Y'); $i >= 2018; $i--){
                ?><option value="<?php echo $i?>"> <?php echo $i?> </option><?php
            }
        ?>
    </select>
</div>
<?php


?>
<div class="gridarea" id="posters"><?php

    ?>
</div>

</div>
</div>
<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/footer.php';
?>
<script src="js/posters.js?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/js/posters.js')?>"></script>
<script type='text/javascript'>
    window.onload = function() {
        getPosters(<?php echo date('Y') ?>);
    };
</script>
</body>
</html>