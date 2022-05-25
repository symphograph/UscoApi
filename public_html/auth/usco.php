<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/includs/config.php';
$User = User::byCheck();

//$_POST = json_decode(file_get_contents('php://input'), true)['params'] ?? null;

if(!$User->Sess) {
    die(http_response_code(401));
}
$redirects = [
    'test.sakh-orch.ru' => 'localhost:8080',
    'sakh-orch.ru' => 'musicrom.ru'
];
$redirect = $redirects[$_SERVER['SERVER_NAME']];
header("Location: https://$redirect/auth#{$User->Sess->getToken()}");