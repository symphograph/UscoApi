<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
require_once dirname($_SERVER['DOCUMENT_ROOT'])."/vendor/autoload.php";
if(!$cfg->myip) exit;

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
/*
$Entryes = Entry::getAlldbRows();
foreach ($Entryes as $en){
    @unlink($_SERVER['DOCUMENT_ROOT'].'/img/entry/1080/' . $en->id . '/.png');
    @unlink($_SERVER['DOCUMENT_ROOT'].'/img/entry/1080/' . $en->id . '/.svg');
    @unlink($_SERVER['DOCUMENT_ROOT'].'/img/entry/1080/' . $en->id . '/.jpeg');

    @unlink($_SERVER['DOCUMENT_ROOT'].'/img/entry/origins/' . $en->id . '/.png');
    @unlink($_SERVER['DOCUMENT_ROOT'].'/img/entry/origins/' . $en->id . '/.svg');
    @unlink($_SERVER['DOCUMENT_ROOT'].'/img/entry/origins/' . $en->id . '/.jpeg');
}
*/
?>



</body>
</html>