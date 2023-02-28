<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use App\{Anonce, User};
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;

$User = User::byCheck();
$User->apiAuth(needPowers: [1,2,4]);

$Anonce = Anonce::addNewAnonce() or
throw new AppErr('addNewAnonce err');

Response::data($Anonce);
