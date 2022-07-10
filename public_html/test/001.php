<?php
//echo $_SERVER['REMOTE_ADDR'];
//echo 'jhfgj';
//print_r($_SERVER);

require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';

//printr(getallheaders());
//echo getallheaders()['Authorization'];
//$User = User::byCheck();
//$User->apiAuth(100);
//require_once dirname($_SERVER['DOCUMENT_ROOT'])."/vendor/autoload.php";
$opts = Session::cookOpts(samesite: 'Strict');

if (empty($_COOKIE['test1'])){
    setcookie('test1','12345',$opts);
    header("Refresh:0",0);
    die();
}

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
<?php

//printr($_COOKIE['identy']);
//printr($_SERVER);
?>
</body>
</html>