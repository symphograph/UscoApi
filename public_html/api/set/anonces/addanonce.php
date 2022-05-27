<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/includs/config.php';
$User = User::byCheck();
$User->apiAuth(90);


$Anonce = Anonce::addNewAnonce() or
die(APIusco::errorMsg('Какая досада!'));

echo APIusco::resultData($Anonce);
