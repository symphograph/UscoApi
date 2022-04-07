<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
$User = User::byCheck();
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/functions/filefuncts.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/functions/galleryfuncts.php';
$shared_folder_link = 'https://yadi.sk/d/1epqAPRtCaMHKQ';
$albumsdir = 'img/albums/';
if(!empty($_GET['up'])){
    ReplFoldersFromYaDisk($albumsdir,$shared_folder_link);
    header('Location: '.'gallery.php');
}

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
    <?php jsFile('dist/js/jgallery.js');?>
</head>
<body class="body_test">
	
<?php require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/links.php';?>
	
<div class="content">
	<div id="gallery"></div>
	<?php

	?>

	<script type="text/javascript">
		document.querySelector('#gallery').appendChild(JGallery.create([<?php Albums($albumsdir);?>]).getElement());
	</script>
</div>
<script async src="https://culturaltracking.ru/static/js/spxl.js?pixelId=16513" data-pixel-id="16513"></script>
<script type="text/javascript">
    (function(d, t, p) {
        var j = d.createElement(t); j.async = true; j.type = "text/javascript";
        j.src = ("https:" == p ? "https:" : "http:") + "//stat.sputnik.ru/cnt.js";
        var s = d.getElementsByTagName(t)[0]; s.parentNode.insertBefore(j, s);
    })(document, "script", document.location.protocol);
</script>
</body>
</html>