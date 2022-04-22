<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/includs/config.php';
$User = User::byCheck();

//$_POST = json_decode(file_get_contents('php://input'), true)['params'] ?? null;

if(!$User->Sess) {
    die(http_response_code(401));
}
header("Location: https://localhost:8080/auth#{$User->Sess->getToken()}");