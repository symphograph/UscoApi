<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
$User = User::byCheck();
$_POST = json_decode(file_get_contents('php://input'), true)['params'];

$data = NewsItem::apiValidation()
or die(http_response_code(400));

$Item = NewsItem::getJson($data['id'])
or die(http_response_code(204));

echo $Item;