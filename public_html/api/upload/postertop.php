<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use App\{Poster, User};
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\ValidationErr;

User::auth([1, 2, 4]);

if(empty($_FILES)){
    throw new ValidationErr('$_FILES is empty', 'Файлы не доставлены');
}

$file = array_shift($_FILES);
$response = Poster::upload($file, 1);

Response::success();