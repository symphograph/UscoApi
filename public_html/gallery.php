<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/check.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/functions/filefuncts.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/functions/galleryfuncts.php';
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">

    <?php
    $p_title = 'Галерея';
    $ver = random_str(8);
    ?>
    <title><?php echo $p_title;?></title>
    <link rel="icon" href="img/logo/logo.svg" sizes="any" type="image/svg+xml">
    <?php CssMeta(['right_nav.css','photo2.css'])?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<!--    <script type="text/javascript" src="https://code.jquery.com/jquery-latest.js"></script>-->
    <script type="text/javascript" src="js/dist/js/jgallery.min.js?v=2.2.1edit"></script>
</head>
<body class="body_test">
	
<?php require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/links.php';?>
	
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
<script type="text/javascript" src="https://culturaltracking.ru/static/js/spxl.js?pixelId=1032" data-pixel-id="1032"></script>
</body>
</html>