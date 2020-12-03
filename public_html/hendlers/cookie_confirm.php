<?php
if(empty($_POST['confirm']))
    die();
$root = $_SERVER['DOCUMENT_ROOT'];
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/check.php';

if(isset($_COOKIE['identy']))
{
    qwe("UPDATE `identy` SET `cookie_confirm` = '1' WHERE `identy` = '$identy'");
    $cooktime = time()+60*60*24*365;
    setcookie('cookok',1,$cooktime,'/','',true,true);
}
echo 'ok';