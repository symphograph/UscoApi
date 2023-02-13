<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
User::authByToken(needPowers: [1,2,4]);

if(empty($_FILES)){
    die(http_response_code(400));
}



$file = array_shift($_FILES);
$response = Poster::upload($file, 1);

header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_UNICODE);