<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/includs/config.php';
use api\Api;

$token = $_POST['token'] or die(Api::errorMsg('emptyToken'));
$User = User::byCheck();
if(!$User->Sess){
    die(Api::errorMsg('sessError'));
}
//$Sess = Session::byToken($token)
    //or die(die(Api::errorMsg('badToken')));
//printr($User);
$User->apiAuth();
echo Api::resultData([
    'lvl'    => $User->lvl,
    'server' => $_SERVER['SERVER_NAME']
]);