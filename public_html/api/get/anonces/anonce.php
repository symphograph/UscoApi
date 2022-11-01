<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/includs/config.php';
$User = User::byCheck();


$id = intval($_POST['id'] ?? 0)
or die(http_response_code(400));

Anonce::reCache($id);
//$Anonce = Anonce::getReady($id)
$Anonce = Anonce::byCache($id)
    or die(http_response_code(204));

//echo json_encode($Anonce);
echo $Anonce;

//echo AnoncePaje::getJson($data['id']);