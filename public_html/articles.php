<?php
$root = $_SERVER['DOCUMENT_ROOT'];
include_once $root.'/includs/ip.php';
include_once $root.'/functions/functions.php';
include_once $root.'/includs/check.php';
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<?php
$p_title = 'Статьи';
$ver = random_str(8);
?>
<title><?php echo $p_title;?></title>
    <link rel="icon" href="img/logo/logo.svg" sizes="any" type="image/svg+xml">
<link href="css/menu.css?ver=<?php echo md5_file('css/menu.css');?>" rel="stylesheet">
<link href="css/index.css?ver=<?php echo md5_file('css/index.css');?>" rel="stylesheet">
<link href="css/articles.css?ver=<?php echo md5_file('css/articles.css');?>" rel="stylesheet">
<link href="css/menum.css?ver=<?php echo md5_file('css/menum.css');?>" rel="stylesheet">
<link href="css/right_nav.css?ver=<?php echo md5_file('css/right_nav.css');?>" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://vk.com/js/api/openapi.js?159" type="text/javascript"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-latest.js"></script>
</head>

<body>
<?php
include 'includs/links.php';
include 'includs/header.php';
?>


<div class="content">


<script type="text/javascript" src="https://vk.com/js/api/openapi.js?159"></script>
<div class="eventsarea">
<div class="artbox">
<div class="article">
<div id="my_article-tigran-ahnazaryan"></div>
<script type="text/javascript">
VK.Widgets.Article('my_article-tigran-ahnazaryan', '@-166038484-tigran-ahnazaryan');
</script>
</div>
</div>

<div class="artbox">
<div class="article">
<div id="my_article-molitva-orkestru"></div>
<script type="text/javascript">
VK.Widgets.Article('my_article-molitva-orkestru', '@-166038484-molitva-orkestru');
</script>
</div>
</div>
</div>
</div>


<?php
include 'includs/footer.php';
?>
</body>
</html>