<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/includs/config.php';
$User = User::byCheck();
$User->apiAuth(90);


$Entry = Entry::addNewEntry() or
die(APIusco::errorMsg('Какая досада!'));

echo APIusco::resultData($Entry);
