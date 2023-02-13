<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';
use App\{Hall, User};

$User = User::byCheck();


$halls = Hall::getList();
$opts = [];
foreach ($halls as $hall){
    $opts [] = [
        'label'=>$hall->name,
        'value'=>$hall->id
    ];
}
header('Content-Type: application/json');
echo json_encode($opts, JSON_HEX_QUOT);