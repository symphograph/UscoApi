<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
$User = User::byCheck();

if(!$User->Sess) {
    die(http_response_code(401));
}

if(!$User->apiAuth()) {
    die(json_encode(['status'=>'badToken']));
}

echo json_encode(['status'=>'ok']);