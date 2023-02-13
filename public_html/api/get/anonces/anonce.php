<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use App\{Anonce, User};

$User = User::byCheck();


$id = intval($_POST['id'] ?? 0)
or die(http_response_code(400));

Anonce::reCache($id);
$Anonce = Anonce::byCache($id)
    or die(http_response_code(204));

echo json_encode($Anonce,JSON_UNESCAPED_UNICODE);