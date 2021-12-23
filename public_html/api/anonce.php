<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/check.php';
$_POST = json_decode(file_get_contents('php://input'), true)['params'];

$data = AnoncePaje::apiValidation()
or die(http_response_code(400));

$Anonce = AnoncePaje::getJson($data['id'])
or die(http_response_code(204));

echo AnoncePaje::getJson($data['id']);