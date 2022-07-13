<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';

$opts = Session::cookOpts(samesite: 'Strict');

if (empty($_COOKIE['test1'])){
    setcookie('test1','12345',$opts);
    header("Refresh:0",0);
    die();
}
echo 'jhgfjh';
//$User = User::byCheck();
printr($_COOKIE);
printr($_GET);
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Тест</title>
    <?php CssMeta(['menu.css','index.css','news.css','menum.css', 'right_nav.css'])?>
</head>

<body>

</body>
</html>