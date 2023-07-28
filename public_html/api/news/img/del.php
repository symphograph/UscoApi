<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use App\{APIusco, Entry, User};
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\Errors\ValidationErr;
use Symphograph\Bicycle\FileHelper;

User::auth([1, 2, 4]);

$id = intval($_POST['id'] ?? 0) or
throw new ValidationErr('id');

$img = $_POST['img'] ?? '';
if (empty($img) or !is_string($img)){
    throw new ValidationErr('img');
}

$file1080 = $_SERVER['DOCUMENT_ROOT'] . Entry::imgFolder . $id . '/' . $img;
$fileOrig = $_SERVER['DOCUMENT_ROOT'] . '/img/entry/origins/' . $id . '/' . $img;

if (FileHelper::fileExists($file1080)){
    unlink($file1080);
}

if (FileHelper::fileExists($fileOrig)){
    unlink($fileOrig);
}

Response::success();