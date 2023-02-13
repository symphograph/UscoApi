<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use App\APIusco;
use App\User;

$token = $_POST['token'] or die(Apiusco::errorMsg('emptyToken'));
$User = User::byCheck();
if(!$User->Sess){
    die(Apiusco::errorMsg('sessError'));
}

$User->apiAuth();
echo APIusco::resultData([
    'Powers' => $User->Powers ?? [],
    'lvl'    => $User->lvl,
    'server' => $_SERVER['SERVER_NAME']
]);