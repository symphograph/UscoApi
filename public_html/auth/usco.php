<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/includs/config.php';
$User = User::byCheck(0);

$debug = (!empty($_GET['debug']) && $env->myip);

if(!$User->Sess) {
    die(http_response_code(401));
}

$User->goToSPA($debug);