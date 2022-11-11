<?php
$start = microtime(true);
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>test</title>
</head>
<body style="color: white; background-color: #262525">
<?php
$a = 'Ano';
$b = 'nce';
$className = $a . $b;
$Anonce = new $className;

use \Symphograph\Bicycle\TestRel;
TestRel::itWorks();
//printr($Anonce);
echo '<br>Время выполнения скрипта: ' . round(microtime(true) - $start, 4) . ' сек.';
?>
</body>