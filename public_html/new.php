<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root.'/../includs/check.php';
?>
<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">
<?php
$new_id = $_GET['new_id'] ?? 0;
$new_id = intval($new_id);
if(!$new_id)
{
    header("Location: ../index.php");
    die();
}

$query = qwe("
SELECT * from `news`
WHERE `new_id` = '$new_id'
");
foreach($query as $q)
{
	$img = $q['img'];
	$img = '<img src="'.$img.'" width="320px"/>';
	$ntitle = $q['new_tit'];	
}	

$p_title = 'Южно-Сахалинский камерный оркестр';
$ver = random_str(8);
?>
<title><?php echo $p_title;?></title>
    <link rel="icon" href="img/logo/logo.svg" sizes="any" type="image/svg+xml">
    <?php CssMeta(['menu.css','index.css','news.css','menum.css', 'right_nav.css'])?>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
<?php
require_once $root.'/../includs/links.php';
require_once $root.'/../includs/header.php';
$host = 'https://'.$_SERVER['SERVER_NAME'].'/';
?>


<div class="content">
<div class="newsarea">
    <div class="ntitle"><?php echo $ntitle;?></div><hr>
	<div class="narea">

	<?php
	include 'news/new_'.$new_id.'.php';
	?></div>

</div>
</div>
<?php
require_once $root.'/../includs/footer.php';
?>
</body>
</html>