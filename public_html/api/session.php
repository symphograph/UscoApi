<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
$User = User::byCheck();

$_POST = json_decode(file_get_contents('php://input'), true)['params'];

$copts = Session::cookOpts(expires: 3600*24);
setcookie('tttest','hgfdhg',$copts);
printr($copts);
if(!$User->Sess) {
    die(http_response_code(401));
}

echo json_encode(['data' => ['ok']]);