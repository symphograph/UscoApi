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
<meta 
	name="sputnik-verification" 
	content="jjcPO4sqQYWv7K37"
/>
<meta name="yandex-verification" content="b82701a7766ca759" />
<meta name="yandex-verification" content="50b98ccfb33aa708" />
<?php
$p_title = 'Южно-Сахалинский камерный оркестр';
$ver = random_str(8);
?>
<title><?php echo $p_title;?></title>
<link href="css/menu.css?ver=<?php echo md5_file('css/menu.css');?>" rel="stylesheet">
<link href="css/index.css?ver=<?php echo md5_file('css/index.css');?>" rel="stylesheet">
<link href="css/menum.css?ver=<?php echo md5_file('css/menum.css');?>" rel="stylesheet">
<link href="css/right_nav.css?ver=<?php echo md5_file('right_nav.css')?>" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://vk.com/js/api/openapi.js?154" type="text/javascript"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-latest.js"></script>
</head>

<body>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v3.1';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>
<?php
include 'includs/links.php';
include 'includs/header.php';
$host = 'https://'.$_SERVER['HTTP_HOST'].'/';
?>

<div class="content">

<div class="eventsarea">
<script type="text/javascript">
       (function(d, t, p) {
           var j = d.createElement(t); j.async = true; j.type = "text/javascript";
           j.src = ("https:" == p ? "https:" : "http:") + "//stat.sputnik.ru/cnt.js";
           var s = d.getElementsByTagName(t)[0]; s.parentNode.insertBefore(j, s);
       })(document, "script", document.location.protocol);
</script>
<?php
VideoItems();
?>

</div>
</div>
<?php
include 'includs/footer.php';
?>
 
</body>
</html>