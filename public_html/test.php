<?php
$root = $_SERVER['DOCUMENT_ROOT'];
include_once $_SERVER['DOCUMENT_ROOT'].'/includs/ip.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/functions/functions.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/includs/config.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/includs/config2.php';
if(!$myip) exit;
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Тест</title>
</head>

<body>
<?php

include $_SERVER['DOCUMENT_ROOT'].'/../admin/ttt.php';
?>
</body>
</html>