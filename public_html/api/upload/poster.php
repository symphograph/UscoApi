<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use App\Poster;
use App\User;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\Errors\ValidationErr;

User::authByToken(needPowers: [1,2,4]);

if(empty($_FILES)){
    throw new ValidationErr('$_FILES is empty', 'Файлы не доставлены');
}

$file = array_shift($_FILES);
$response = Poster::upload($file);
if(!empty($response->error)){
    throw new AppErr($response->error, $response->error);
}

Response::success();