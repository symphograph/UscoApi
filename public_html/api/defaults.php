<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
$User = User::byCheck();
$_POST = json_decode(file_get_contents('php://input'), true)['params'];

$dafaults = [
    'lastAlbum' => Album::getLast()
];
echo json_encode(['data' => $dafaults]);