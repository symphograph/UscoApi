<?php
if(empty($_POST['confirm']))
    die();
$root = $_SERVER['DOCUMENT_ROOT'];
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
$User = User::byCheck();

if(isset($_COOKIE['identy']))
{
    qwe("UPDATE `identy` SET `cookie_confirm` = '1' WHERE `identy` = :identy",['identy'=>$User->identy]);
    $opts = Session::cookOpts(expires: time()+60*60*24*365);
    //$cooktime = time()+60*60*24*365;
    setcookie('cookok',1,$opts);
}
echo 'ok';