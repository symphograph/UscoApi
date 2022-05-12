<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
$User = User::byCheck();
$_POST = json_decode(file_get_contents('php://input'), true)['params'];

$data = Anonce::apiValidation()
or die(http_response_code(400));

$qwe = AnonceCard::getCollection($data['sort'],$data['year'],$data['new'])
or die(http_response_code(204));

echo json_encode(['data' => $qwe],JSON_UNESCAPED_UNICODE);