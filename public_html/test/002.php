<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Тест</title>
</head>

<body style="color: white; background-color: #262525">
<?php
echo 'jkgjhk';
$Item = Entry::byID(107);

printr($Item);
?>
</body>
</html>