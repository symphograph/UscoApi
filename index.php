<?php
session_start();
require_once 'includs/ip.php';
require_once 'functions/functions.php';
require_once 'includs/check.php';
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta 
	name="sputnik-verification" 
	content="jjcPO4sqQYWv7K37"
/>
<script src="https://culturaltracking.ru/static/js/spxl.js" data-pixel-id="1032"></script>
<meta name="yandex-verification" content="b82701a7766ca759" />
<meta name="yandex-verification" content="50b98ccfb33aa708" />
<?php
$p_title = 'Южно-Сахалинский камерный оркестр';
$ver = random_str(8);
?>
<title><?php echo $p_title;?></title>
<link href="css/menu.css?ver=<?php echo md5_file('css/menu.css');?>" rel="stylesheet">
<link href="css/index.css?ver=<?php echo md5_file('css/index.css');?>" rel="stylesheet">
<link href="css/afisha.css?ver=<?php echo md5_file('css/afisha.css');?>" rel="stylesheet">
<link href="css/menum.css?ver=<?php echo md5_file('css/menum.css');?>" rel="stylesheet">
<link href="css/right_nav.css?ver=<?php echo md5_file('right_nav.css')?>" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://vk.com/js/api/openapi.js?154" type="text/javascript"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-latest.js"></script>
</head>

<body>

<?php
FacebookScript();
include 'includs/links.php';
include 'includs/header.php';
$host = 'https://'.$_SERVER['HTTP_HOST'].'/';
?>

<!--<div class="ubis"><b>XX ЮБИЛЕЙНЫЙ СЕЗОН</b></div>-->
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
$query = qwe("
SELECT
anonces.concert_id as ev_id,
anonces.hall_id,
anonces.prog_name,
anonces.sdescr,
anonces.description,
anonces.img,
anonces.topimg,
anonces.aftitle,
anonces.datetime,
anonces.pay,
anonces.age,
anonces.ticket_link,
halls.hall_name,
halls.map
FROM
anonces
INNER JOIN halls ON anonces.hall_id = halls.hall_id
WHERE /*anonces.concert_id > 3 AND*/ datetime >= NOW()
ORDER BY anonces.datetime
");
$prrows = ['','','Вход свободный','Билеты в продаже','Вход по пригласительным','Билеты в продаже'];
foreach($query as $q)
{
ConcertItem($q);
}
if(!$query or mysqli_num_rows($query) == 0)
	VideoItems();
?>
</div>

<div class="vkcom">
<?php
    NewsCol();
    FacebookCol();
?>

<br><hr><br>
<script type="text/javascript" src="https://vk.com/js/api/openapi.js?154"></script>

<!-- VK Widget -->
<div id="vk_groups"></div>
<script type="text/javascript">
VK.Widgets.Group("vk_groups", {mode: 4, wide: 1, no_cover: 0, height: "800", width: "auto", color1: 'e7ddcb',color3: 'A98700'}, 166038484);
</script>
</div>
</div>
<?php
include 'includs/footer.php';
?>
 
</body>
</html>