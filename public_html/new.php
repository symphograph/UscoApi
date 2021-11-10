<?php
$new_id = $_GET['new_id'] ?? 0;
$new_id = intval($new_id);
if(!$new_id)
{
    header("Location: ../news.php");
    die();
}
$root = $_SERVER['DOCUMENT_ROOT'];
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/check.php';

$qwe = qwe("
SELECT * from `news`
WHERE `id` = '$new_id'
AND `show`
");
if(!$qwe or !$qwe->rowCount())
{
    header("Location: ../news.php");
    die();
}
$q = $qwe->fetchObject();

$p_title = 'Южно-Сахалинский камерный оркестр';
$ver = random_str(8);
$indexes = ['noindex','index'];
$index = $indexes[intval($q->show == 1)];
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title><?php echo $p_title;?></title>
    <link rel="icon" href="img/logo/logo.svg" sizes="any" type="image/svg+xml">
    <?php CssMeta(['menu.css','index.css','news.css','menum.css', 'right_nav.css'])?>
    <meta name="robots" content="<?php echo $index?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/links.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/header.php';
$host = 'https://'.$_SERVER['SERVER_NAME'].'/';
?>


<div class="content">
    <?php
    $NewsItem = new NewsItem(id: $new_id);
    //$NewsItem->byQ($q);
    echo $NewsItem->PajeItem();
    ?>
</div>
<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/footer.php';
?>
</body>
</html>