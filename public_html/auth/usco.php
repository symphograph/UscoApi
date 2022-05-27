<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/includs/config.php';
$User = User::byCheck();
$path = $_GET['path'] ?? '/anonce/100';
//printr($path);
//die();
$path = PathHelper::validPath($path);
//printr($path);
//die();
//$_POST = json_decode(file_get_contents('php://input'), true)['params'] ?? null;

if(!$User->Sess) {
    die(http_response_code(401));
}
$redirects = [
    'test.sakh-orch.ru' => 'localhost:8080',
    'sakh-orch.ru' => 'dev.sakh-orch.ru'
];
$redirect = $redirects[$_SERVER['SERVER_NAME']];
//$path = '/anonces';
header("Location: https://$redirect/auth?path=$path#{$User->Sess->getToken()}");