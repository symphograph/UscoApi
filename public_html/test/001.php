<?php

use App\Announce;
use App\Test\TestClass;
use App\Test\TestClass2;

require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

$start = microtime(true);
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>test</title>
</head>
<body style="color: white; background-color: #262525">
<?php
$id = 140;
printr(ob_list_handlers());
echo ob_get_clean();
$Announce = Announce::byId($id);
printr($Announce);

echo '<br>Время выполнения скрипта: ' . round(microtime(true) - $start, 4) . ' сек.';
?>
</body>