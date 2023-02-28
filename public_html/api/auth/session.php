<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use App\User;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AuthErr;


$User = User::byCheck();
if(!$User->Sess){
    throw new AuthErr('sessError', 'Ошибка сессии');
}

$User->apiAuth();
Response::data([
    'Powers' => $User->Powers ?? [],
    'lvl'    => $User->lvl,
    'server' => $_SERVER['SERVER_NAME']
]);