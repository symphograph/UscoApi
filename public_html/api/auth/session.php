<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/includs/config.php';
use api\Api;

$token = $_POST['token'] or die(Api::errorMsg('emptyToken'));
$User = User::byCheck();
if(!$User->Sess){
    die(Api::errorMsg('sessError'));
}

$User->apiAuth();
echo Api::resultData([
    'Powers' => $User->Powers ?? [],
    'lvl'    => $User->lvl,
    'server' => $_SERVER['SERVER_NAME']
]);