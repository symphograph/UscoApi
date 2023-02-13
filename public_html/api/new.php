<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';
use App\Entry;
use App\User;

$User = User::byCheck();
$_POST = json_decode(file_get_contents('php://input'), true)['params'];

$id = intval($_POST['id'] ?? 0)
    or die(http_response_code(400));

$Item = Entry::byID($id)
    or die(http_response_code(204));
//$Item->Images = json_encode($Item->Images);
echo json_encode($Item);