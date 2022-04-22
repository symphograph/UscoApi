<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/functions/functions.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
if(!$cfg->myip) exit;
//$User = User::byCheck();
//$User->apiAuth(100);

?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Тест</title>
    <?php CssMeta(['menu.css','index.css','news.css','menum.css', 'right_nav.css'])?>
</head>

<body>
<?php


?>
</body>
</html>