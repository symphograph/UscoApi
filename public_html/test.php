<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
if(!$env->myip) exit;
//$User = User::byCheck();
//$User->apiAuth(100);
require_once dirname($_SERVER['DOCUMENT_ROOT'])."/vendor/autoload.php";

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