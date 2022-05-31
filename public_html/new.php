<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';

$new_id = intval($_GET['new_id'] ?? 0) ;
if(!$new_id)
{
    header("Location: ../news.php");
    die();
}

$User = User::byCheck();

//$NewsItem = new NewsItem(id: $new_id);
$NewsItem = Entry::byID($new_id);

if($NewsItem->evid)
{
    header("Location: ../event.php?evid={$NewsItem->evid}");
    die();
}

$p_title = 'Новости';
$ver = random_str(8);
$indexes = ['noindex','index'];
$index = $indexes[intval(in_array($NewsItem->show,[1,3]))];
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title><?php echo $NewsItem->title;?></title>
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
    //echo $NewsItem->PajeItem();
    echo $NewsItem->htmlPaje();
    ?>
</div>
<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/footer.php';
?>
</body>
</html>