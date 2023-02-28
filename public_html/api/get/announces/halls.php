<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';
use App\{Hall, User};
use Symphograph\Bicycle\Api\Response;

$User = User::byCheck();


$halls = Hall::getList();
$opts = [];
foreach ($halls as $hall){
    $opts [] = [
        'label'=>$hall->name,
        'value'=>$hall->id
    ];
}
Response::data($opts);