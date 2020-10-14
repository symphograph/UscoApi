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
if(!$new_id > 0)
exit("<meta http-equiv='refresh' content='0; url=index.php'>");
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
<link href="css/menu.css?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/css/menu.css');?>" rel="stylesheet">
<link href="css/index.css?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/css/index.css');?>" rel="stylesheet">
<link href="css/news.css?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/css/menum.css');?>" rel="stylesheet">
<link href="css/menum.css?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/css/index.css');?>" rel="stylesheet">
<link href="css/right_nav.css?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/css/right_navcss');?>" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script type="text/javascript" src="https://code.jquery.com/jquery-latest.js"></script>
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