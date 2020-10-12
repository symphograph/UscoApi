
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Тест пути</title>
</head>

<body>
<?php
//Функция проверяет мой ip
echo 'HTTP_HOST';
include_once 'https://'.$_SERVER['HTTP_HOST'].'/includs/ip.php';
if($myip)
	echo ('Функцию видно1');
if($myip == NULL)
	echo 'Функция не подключилась<br>';
echo 'DOCUMENT_ROOT';
include_once $_SERVER['DOCUMENT_ROOT'].'/includs/ip.php';
if($myip)
	echo ('Функцию видно2');
if($myip == NULL)
	echo 'Функция не подключилась<br>';
include_once 'https://'.$_SERVER['HTTP_HOST'].'/functions/functions.php';
//var_dump($_SERVER['HTTP_HOST']);

echo '<br>HTTP_HOST';
?>
<br><img src="<?php echo 'https://'.$_SERVER['HTTP_HOST'].'/img/logo.png'?>" width="50px"><br>
<?php echo 'DOCUMENT_ROOT'; ?>
<br><img src="<?php echo 'https://'.$_SERVER['DOCUMENT_ROOT'].'/img/logo.png'?>" width="50px">
<br><img src="<?php echo $_SERVER['DOCUMENT_ROOT'].'/img/logo.png'?>" width="50px">
<?php

?>
</body>
</html>