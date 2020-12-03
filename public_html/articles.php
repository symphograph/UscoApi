<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/check.php';
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <?php
    $p_title = 'Статьи';
    $ver = random_str(8);
    ?>
    <title><?php echo $p_title;?></title>
    <link rel="icon" href="img/logo/logo.svg" sizes="any" type="image/svg+xml">
    <?php CssMeta(['menu.css','index.css','articles.css','menum.css', 'right_nav.css'])?>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://vk.com/js/api/openapi.js?159" type="text/javascript"></script>
</head>

<body>
<?php
include ROOT.'/includs/links.php';
include ROOT.'/includs/header.php';
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
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/footer.php';
?>
</body>
</html>