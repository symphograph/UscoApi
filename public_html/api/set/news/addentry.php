<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use App\{APIusco, Entry, User};

$User = User::byCheck();
$User->apiAuth(needPowers: [1,2,4]);


$Entry = Entry::addNewEntry() or
die(APIusco::errorMsg('Какая досада!'));

echo APIusco::resultData($Entry);
