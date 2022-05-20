<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
$User = User::byCheck();
$_POST = json_decode(file_get_contents('php://input'), true)['params'];

$data = NewsItem::apiValidation()
or die(http_response_code(400));

$Item = Entry::byID($data['id'])
or die(http_response_code(204));
//$Item->Images = json_encode($Item->Images);
echo json_encode($Item);