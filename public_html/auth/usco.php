<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use App\User;

$User = User::byCheck(0);

if(!$User->Sess) {
    die(http_response_code(401));
}

$User->goToSPA();