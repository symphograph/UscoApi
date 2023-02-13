<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use App\Video;

$_POST = json_decode(file_get_contents('php://input'), true)['params'] ?? null;
$limit = $_POST['limit'] ?? 1000;
$limit = intval($limit);
$VideoList = Video::getCollection($limit);
if(!$VideoList) {
    die(http_response_code(401));
}

echo $VideoList;