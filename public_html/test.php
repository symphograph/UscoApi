<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
if(!$cfg->myip) exit;
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
if(empty($_COOKIE['identy'])){
    echo 'hghgfgh';

}
$qwe = qwe("
SELECT * FROM identy 
WHERE identy = :identy",
    ['identy'=>$_COOKIE['identy']]
);
printr($qwe->fetchObject());
?>
</body>
</html>