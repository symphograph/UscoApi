<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
$User = User::byCheck();
$User->apiAuth();

echo json_encode([
    'status'=>'ok',
    'lvl' => $User->lvl
                 ]);