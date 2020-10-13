<?php

require_once 'includs/ip.php';
if(!$myip and !$officeip) exit();
include_once 'functions/functions.php';
include_once 'functions/setseat.php';
include_once 'includs/check.php';
?>
<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">
<?php
$p_title = 'Нумератор билетов';
$ver = random_str(8);
?>
<title><?php echo $p_title;?></title>
<link href="css/menu.css?ver=<?php echo $ver;?>" rel="stylesheet">
<link href="css/index.css?ver=<?php echo $ver;?>" rel="stylesheet">
<link href="css/news.css?ver=<?php echo $ver;?>" rel="stylesheet">
<link href="css/menum.css?ver=<?php echo $ver;?>" rel="stylesheet">
<link href="css/right_nav.css?ver=<?php echo $ver?>" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://vk.com/js/api/openapi.js?154" type="text/javascript"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-latest.js"></script>
</head>

<body>
<?php
require_once $root.'/includs/links.php';
require_once $root.'/includs/header.php';
$host = 'https://'.$_SERVER['SERVER_NAME'].'/';
$ev_id = 8;
?>


<div class="content">
<div class="text">
<form action="" method="post">
<?php
if(!empty($_POST['set']) and count($_POST['start']) == 6)
{
	foreach($_POST['start'] as $gr =>$num)
	{
		$gr = intval($gr);
		$num = intval($num);
		$stop = intval($_POST['stop'][$gr]);
		for($num = $num;$num<=$stop;$num++)
		{
			//echo $num.' | '.$gr.'<br>';
			$nums[$num] = $gr;
		}
	}

	SetSeatNums($ev_id,$nums);
	foreach($_POST['price'] as $k =>$v)
	{
		$k = intval($k);
		$v = intval($v);
		qwe("
		UPDATE `rodina_price`
		SET `price` = '$v'
		WHERE `ev_id` = '$ev_id'
		AND `group_id` = '$k'
		");
		
	}
	
}
$query = qwe("
SELECT
`rodina_price`.`group_id`,
`rodina_price`.`price`,
min(`rodinums`.`tic_num`) as `min`,
max(`rodinums`.`tic_num`) as `max`,
count(`rodina_price`.`price`) as cnt,
sum(`rodina_price`.`price`) as sumpr
FROM
`rodinums`
INNER JOIN `rodina_price` ON `rodinums`.`ev_id` = `rodina_price`.`ev_id`
INNER JOIN `rodina` ON `rodinums`.`seat_id` = `rodina`.`seat_id` AND `rodina_price`.`group_id` = `rodina`.`group_id`
WHERE `rodina_price`.`ev_id` = '$ev_id'
GROUP BY `group_id`
");
if(mysqli_num_rows($query)>0)
{
	foreach($query as $q)
	{
		$start = $q['min'];
		$stop = $q['max'];
		$price = $q['price'];
		$sum[] = $q['sumpr'];
		$cnt[] = $q['cnt'];
		?>
		от<input name="start[<?php echo $q['group_id'];?>]" value="<?php echo $start;?>" type="number">
		до<input name="stop[<?php echo $q['group_id'];?>]" value="<?php echo $stop;?>" type="number">
		цена<input name="price[<?php echo $q['group_id'];?>]" value="<?php echo $price;?>" type="number">
		кол-во:<?php echo $q['cnt'];?> | сумма: <?php echo $q['sumpr'];?>
		<br>
		
		<?php
		if(!$start > 0)
		{
		?>Неверный диапазон номеров!<br><?php
		}
		
	}
	$sum = array_sum($sum);
	$cnt = array_sum($cnt);
	echo 'Итого: <b>'.$sum.'</b>р за <b>'.$cnt.'</b> шт.<br>';
}
else
for($i=1;$i<=6;$i++)
{
?>
от<input name="start[<?php echo $i;?>]" type="number">
до<input name="stop[<?php echo $i;?>]" type="number">
цена<input name="price[<?php echo $i;?>]" type="number"><br>
<?php
}
?>
<input name="set" value="Сохранить" type="submit">
</form>
<?php
if(mysqli_num_rows($query)>0)
{
	
	$sorts = ['`tic_num`','`row`,`num`'];
	$check = ['',''];
	$get = 0;
	if(!empty($_GET['sort']))
	{
		$get = intval($_GET['sort']);
	}
	$sort = $sorts[$get];
	$check[$get] = ' checked ';
	
	?>
	<br>
	<form action="" method="get">
	<label><input type="radio" name="sort" <?php echo $check[0];?> value="0" onchange="this.form.submit()"/>сортировать по номерам</label>
	<label><input type="radio" name="sort" <?php echo $check[1];?> value="1" onchange="this.form.submit()"/>сортировать по местам</label>
	</form><br>

	<?php
}

$query = qwe("
SELECT
`rodina`.`seat_id`,
`rodina`.`row`,
`rodina`.`num`,
`rodina`.`group_id`,
`rodinums`.`tic_num`,
`rodina_price`.`price`
FROM
`rodina`
INNER JOIN `rodinums` ON `rodina`.`seat_id` = `rodinums`.`seat_id`
LEFT JOIN `rodina_price` ON `rodinums`.`ev_id` = `rodina_price`.`ev_id` AND `rodina`.`group_id` = `rodina_price`.`group_id`
WHERE `rodinums`.`ev_id` = 8
ORDER BY ".$sort."
");
foreach($query as $q)
{
	echo $q['tic_num'].' - Ряд: '.$q['row'].' | место:'.$q['num'].'<br>';
}
/*
foreach($seatnums as $sid => $tnum)
{
	if($tnum>0)
	echo $tnum.' - Ряд: '.$rows[$sid].' | место:'.$snums[$sid].'<br>';
}
*/
//printr(array_count_values($groups1));
?>
</div>
</div>
<?php
require_once $root.'/includs/footer.php';
?>
</body>
</html>