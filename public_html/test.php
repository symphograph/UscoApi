<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root.'/../includs/ip.php';
require_once $root.'/../functions/functions.php';
require_once $root.'/../includs/config.php';
require_once $root.'/../includs/config2.php';
if(!$myip) exit;
?>
<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">
<title>Тест</title>
</head>

<body>
<?php
//printr(getenv());

//include $_SERVER['DOCUMENT_ROOT'].'/../admin/ttt.php';
?>
</body>
</html>