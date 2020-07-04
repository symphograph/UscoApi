<?php
include_once 'includs/ip.php';
include_once 'functions/functions.php';
//if(!$myip) exit;
include_once 'functions/filefuncts.php';
include_once 'functions/galleryfuncts.php';
include_once 'includs/check.php';
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<script src="https://culturaltracking.ru/static/js/spxl.js" data-pixel-id="1032"></script>
<?php
$p_title = 'Галерея';
$ver = random_str(8);
?>
<title><?php echo $p_title;?></title>
<link href="css/right_nav.css?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/css/right_nav.css');?>" rel="stylesheet">
<link href="css/photo2.css?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/css/photo2.css');?>" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script type="text/javascript" src="https://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="js/dist/js/jgallery.min.js?v=2.2.1edit"></script>
</head>
<body class="body_test">
	
<?php include_once 'includs/links.php';?>
	
<div class="content">
	<div id="gallery"></div>
	<?php
	$shared_folder_link = 'https://yadi.sk/d/1epqAPRtCaMHKQ';
	$albumsdir = 'img/albums/';
	if(!empty($_GET['up']))
	ReplFoldersFromYaDisk($albumsdir,$shared_folder_link);
	?>

	<script type="text/javascript">
		document.querySelector('#gallery').appendChild(JGallery.create([<?php Albums($albumsdir);?>]).getElement());
	</script>
</div>
</body>
</html>