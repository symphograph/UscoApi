<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/includs/config.php';
$User = User::byCheck();
//$_POST = json_decode(file_get_contents('php://input'), true)['params'];\

$halls = Hall::getCollection();
$opts = [];
foreach ($halls as $hall){
    $opts [] = [
        'label'=>$hall->name,
        'value'=>$hall->id
    ];
}
header('Content-Type: application/json');
echo json_encode($opts, JSON_HEX_QUOT);