<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use App\User;

?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Тест</title>
</head>

<body style="color: white; background-color: #262525">
<?php
$powers = User::getPowers(5);
printr($powers);

echo 'Время выполнения скрипта: ' . round(microtime(true) - $start, 4) . ' сек.';
?>
</body>
</html>