<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use App\{Anonce, APIusco, User};

$User = User::byCheck();
$User->apiAuth(needPowers: [1,2,4]);

$Anonce = Anonce::addNewAnonce() or
die(APIusco::errorMsg('Какая досада!'));

echo APIusco::resultData($Anonce);
