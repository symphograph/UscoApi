<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root.'/includs/check.php';

?>
<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">


<?php
$p_title = 'Южно-Сахалинский камерный оркестр';
$ver = random_str(8);
?>
<title><?php echo $p_title;?></title>
<link href="css/menu.css?ver=<?php echo $ver;?>" rel="stylesheet">
<link href="css/index.css?ver=<?php echo $ver;?>" rel="stylesheet">
<link href="css/afisha.css?ver=<?php echo $ver;?>" rel="stylesheet">
<link href="css/menum.css?ver=<?php echo $ver;?>" rel="stylesheet">
<link href="css/right_nav.css?ver=<?php echo $ver?>" rel="stylesheet">
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
}(document, 'script', 'facebook-jssdk'));</script>
<?php
require_once $root.'/includs/links.php';
require_once $root.'/includs/header.php';
$host = 'https://'.$_SERVER['SERVER_NAME'].'/';
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
WHERE anonces.concert_id > 3
ORDER BY anonces.datetime DESC
");
$prrows = ['','','Вход свободный','Билеты в продаже','Вход по пригласительным','Билеты в продаже'];
foreach($query as $q)
{
	extract($q);
	//$ev_id = $q['concert_id'];
	//$descr = $q['description'];
	//$sdescr = $q['sdescr'];
	//$prog_name = $q['prog_name'];
	if($age > 0)
		$age = '<div class="age">'.$age.'+</div>';
	else
		$age = '';
	
	
	$evdate = strtotime($datetime);
	$evdateru = ru_date('%e&nbsp;%bg&nbsp',$evdate);
	$evtime = date('H:i',$evdate);
	$bg = 'style="background-image: url('.$host.'img/afisha/'.$img.')"' ?? '';
	$bg = '';
	$querypr = qwe("
	SELECT * FROM `rodina_price`
	WHERE `ev_id` = '$ev_id'
	");
	if(mysqli_num_rows($querypr)>0 and $pay == 1)
	{
		$prices = [];
		foreach($querypr as $qp)
		{
			$prices[] = $qp['price'];
		}
		$maxpr = max($prices);
		$minpr = min($prices);
		$prrow = 'Цена: '.$minpr.' - '.$maxpr.' р.' ?? '';
		$byebtn = '
		<div><br><p><span>'.$prrow.'</span></p><br>
		<p>
		
		<div class="bybtn"><span class="bybtntxt">Купить билет</span></div>
		
		</p>
		</div>';
	}else
	{
		/*
		if($q['pay'] == 2)
			$prrow = 'Вход свободный';
		else
			$prrow = '';
		if($q['pay'] == 4)
			$prrow = 'Вход по пригласительным';
		*/
		$prrow = $prrows[$pay];
		$byebtn = '
		<div><br>
		<p><span>'.$prrow.'</span></p><br>
		<p><a href="event.php?evid='.$ev_id.'" class="tdno"><div class="bybtn"><span class="bybtntxt">Подробно</span></div></a></p>
		</div>';
	}
	
	if($pay == 5)
	{

		$byebtn = '<div><br>
		<p><span>'.$prrow.'</span></p><br>
		<p><a href="'.$ticket_link.'" class="tdno"><div class="bybtn"><span class="bybtntxt">Купить онлайн</span></div></a></p>
		</div>';
	}
	
	?><div class="eventbox tdno" <?php echo $bg;?>>
	<?php echo $age;?>
	<div class="pressme">
	<div class="affot">
		<img src="<?php echo $host.'img/afisha/'.$topimg;?>" width=260px/>
	</div>
	<br>
		
	<div class="evdate"><?php echo $evdateru.' в '.$evtime;?></div>
	<a href="<?php echo $map;?>" target="_blank"><?php echo $hall_name;?></a>
	
		<div class="aftext">
			<a href="event.php?evid=<?php echo $ev_id;?>" class="tdno">
				<div class="evname"><?php echo $prog_name;?></div>
				<br>
				<div class="sdescr"><?php echo $sdescr?>
					<br><br>
					Художественный руководитель  и главный дирижер - <b>Тигран Ахназарян</b>.
				</div>
			</a>
			<div class="downbox">
				<div class="tdno"><?php echo $byebtn;?></div>
			</div>
		</div>
	
	</div>
	</div><?php
}
?>
</div>

<div class="vkcom">
<?php NewsCol();?>
<div class="fb-page" 
data-href="https://www.facebook.com/SakhalinSymphony/" 
data-tabs="timeline" 
data-small-header="false" 
data-adapt-container-width="true" 
data-hide-cover="false" 
data-show-facepile="true">
<blockquote cite="https://www.facebook.com/SakhalinSymphony/" class="fb-xfbml-parse-ignore">
<a href="https://www.facebook.com/SakhalinSymphony/">Sakhalin Symphony Orchestra</a></blockquote>
</div>
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
require_once $root.'/includs/footer.php';
?>
 
</body>
</html>