<?php
session_start();
include_once 'includs/ip.php';
include_once 'functions/functions.php';
include_once 'includs/check.php';
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="yandex-verification" content="50b98ccfb33aa708" />
<?php
$p_title = 'Новости';
$ver = random_str(8);
?>
<title><?php echo $p_title;?></title>
<link href="css/menu.css?ver=<?php echo md5_file('css/menu.css');?>" rel="stylesheet">
<link href="css/index.css?ver=<?php echo md5_file('css/index.css');?>" rel="stylesheet">
<link href="css/news.css?ver=<?php echo md5_file('css/news.css');?>" rel="stylesheet">
<link href="css/menum.css?ver=<?php echo md5_file('css/menum.css');?>" rel="stylesheet">
<link href="css/right_nav.css?ver=<?php echo md5_file('css/right_nav.css');?>" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://vk.com/js/api/openapi.js?154" type="text/javascript"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-latest.js"></script>
</head>

<body>
<?php
include 'includs/links.php';
include 'includs/header.php';
//$host = 'https://'.$_SERVER['HTTP_HOST'].'/';
?>


<div class="content">
<div class="newsarea2">
<?php
$query = qwe("
SELECT * from `news`
ORDER BY `date` DESC
");
foreach($query as $q)
{
	$img = $q['img'];
	$img = '<img src="'.$img.'" width="350px"/>';
	$ntitle = $q['new_tit'];
	$new_id = $q['new_id'];
	
	//if(!$myip and $new_id == 17) continue;
	$ntext = $q['text'];
	$ndate = strtotime($q['date']);
	$ndate = ru_date('',$ndate);
	?>
	<div class="newbox">
	<div class="newboxin">
	<div class="tnbox">
	<a href="new.php?new_id=<?php echo $new_id;?>">
	<div class="nboxtitle"><b><?php echo $ntitle;?></b></div>
	<?php
	if(!is_null($q['img']))
	{
	?>
	<div class="nfot">
		<img src="<?php echo $q['img'];?>" width=300px/>
	</div>
	<?php
	}
	?>
	
	
	<br>
	<?php echo $ntext;?>
	<br>
	</a>
	</div>
	
	<div class="ndate"><?php echo $ndate;?></div>
	</div>
	</div><?php
}
?>
</div>
</div>
<?php
include 'includs/footer.php';
?>
</body>
</html>