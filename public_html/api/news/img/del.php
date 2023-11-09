<?php
require_once dirname(__DIR__, 4) . '/vendor/autoload.php';

use App\{Entry\Entry, User};
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Env\Server\ServerEnv;
use Symphograph\Bicycle\Errors\ValidationErr;
use Symphograph\Bicycle\FileHelper;

User::auth([1, 2, 4]);

$id = intval($_POST['id'] ?? 0) or
throw new ValidationErr('id');

$img = $_POST['img'] ?? '';
if (empty($img) or !is_string($img)){
    throw new ValidationErr('img');
}

$file1080 = ServerEnv::DOCUMENT_ROOT() . Entry::imgFolder . $id . '/' . $img;
$fileOrig = ServerEnv::DOCUMENT_ROOT() . '/img/entry/origins/' . $id . '/' . $img;

if (FileHelper::fileExists($file1080)){
    unlink($file1080);
}

if (FileHelper::fileExists($fileOrig)){
    unlink($fileOrig);
}

Response::success();