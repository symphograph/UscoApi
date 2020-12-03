<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/ip.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/functions/functions.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config2.php';
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
echo ROOT;
?>
</body>
</html>