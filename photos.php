<?php
include_once 'includs/ip.php';
include_once 'functions/functions.php';
include_once 'includs/check.php';
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<?php
$p_title = 'Фотографии';
$ver = random_str(8);
?>
<title><?php echo $p_title;?></title>
<link href="css/menu.css?ver=<?php echo $ver;?>" rel="stylesheet">
<link href="css/index.css?ver=<?php echo $ver;?>" rel="stylesheet">
<link href="css/menum.css?ver=<?php echo $ver;?>" rel="stylesheet">
<link href="css/right_nav.css?ver=<?php echo $ver?>" rel="stylesheet">
<link href="css/photo.css?ver=<?php echo $ver?>" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://vk.com/js/api/openapi.js?154" type="text/javascript"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-latest.js"></script>
</head>

<body>
<?php
include 'includs/links.php';
include 'includs/header.php';
?>


<div class="content">

<div class="eventsarea">
<?php
	
$files = scandir('img/photo/small');
$files = array_diff($files, ['..', '.']);
if($myip)
{
?><form method="post" action="edit/photdel.php"><?php	
}
foreach($files as $fi => $f)
{
	echo '<div style="background-image: url(img/photo/small/'.$f.')" class="prewphot">';
	if($myip)
		echo '<input name="photdelete[]" value="'.$f.'" type="checkbox"/>';
	echo '</div>';
}
if($myip)
{
?>
<div class="clear"></div>
<p><input type="submit" value="Удалить отмеченные"/></p>
</form>
<?php	
}
?>

</div>
<?php
	if($myip)
	{
?>
<form method="post" enctype="multipart/form-data" action="upl_photo.php">
			<input type="file" name="picture">
			<br><br>
			<input type="submit" value="Загрузить">
		</form>
<?php		
	}
?>
<script type="text/javascript" src="https://vk.com/js/api/openapi.js?154"></script>
<div class="vkcom">
<!-- VK Widget -->
<div id="vk_groups"></div>
<script type="text/javascript">
VK.Widgets.Group("vk_groups", {mode: 4, wide: 1, no_cover: 1, height: "400", color3: 'A98700'}, 166038484);
</script>
</div>
</div>

<?php
include 'includs/footer.php';
?>
</body>
</html>