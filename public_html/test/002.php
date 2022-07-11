<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
phpinfo();
die();
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Тест</title>
</head>

<body style="color: white; background-color: #262525">
<?php

/*
$start = microtime(true);
for($i=1;$i<2;$i++){
    $qwe = qwe("SELECT concert_id as id FROM anonces WHERE `show` = 1 limit 1");
    foreach ($qwe as $q){
        $Anonce = Anonce::byCache($q['id']);
        //$Anonce = Anonce::getReady($q['id']);
    }
}
*/

echo 'Время выполнения скрипта: ' . round(microtime(true) - $start, 4) . ' сек.';
?>
</body>
</html>