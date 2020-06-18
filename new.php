<?php
//session_start();
include_once 'includs/ip.php';
include_once 'functions/functions.php';
include_once 'includs/check.php';
?>
<!doctype html>
<html>
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
	$img = '<img src="'.$img.'" width="350px"/>';
	$ntitle = $q['new_tit'];	
}	

$p_title = 'Южно-Сахалинский камерный оркестр';
$ver = random_str(8);
?>
<title><?php echo $p_title;?></title>
<link href="css/menu.css?ver=<?php echo $ver;?>" rel="stylesheet">
<link href="css/index.css?ver=<?php echo $ver;?>" rel="stylesheet">
<link href="css/news.css?ver=<?php echo $ver;?>" rel="stylesheet">
<link href="css/menum.css?ver=<?php echo $ver;?>" rel="stylesheet">
<link href="css/right_nav.css?ver=<?php echo $ver?>" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://vk.com/js/api/openapi.js?154" type="text/javascript"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-latest.js"></script>
</head>

<body>
<?php
include 'includs/links.php';
include 'includs/header.php';
$host = 'https://'.$_SERVER['HTTP_HOST'].'/';
?>


<div class="content">
<div class="newsarea">

	<div class="narea">
	<div class="ntitle"><?php echo $ntitle;?></div><hr>
	<?php
	include 'news/new_'.$new_id.'.php';
	?></div>

</div>
</div>
<?php
include 'includs/footer.php';
?>
</body>
</html>