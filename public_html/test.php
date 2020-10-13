<?php
$root = $_SERVER['DOCUMENT_ROOT'];
include_once $root.'/includs/ip.php';
include_once $root.'/functions/functions.php';
include_once $root.'/includs/config.php';
include_once $root.'/includs/config2.php';
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
printr($_ENV);

//include $_SERVER['DOCUMENT_ROOT'].'/../admin/ttt.php';
?>
</body>
</html>