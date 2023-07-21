<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use App\{APIusco, Entry, User};
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\Errors\ValidationErr;

User::auth([1, 2, 4]);

$id = intval($_POST['id'] ?? 0) or
throw new ValidationErr('id');

$img = $_POST['img'] ?? '';
if (empty($img) or !is_string($img)){
    throw new ValidationErr('img');
}

$file1080 = $_SERVER['DOCUMENT_ROOT'] . Entry::imgFolder . $id . '/' . $img;
$fileOrig = $_SERVER['DOCUMENT_ROOT'] . '/img/entry/origins/' . $id . '/' . $img;

if (!file_exists($file1080) || is_dir($file1080)){
    throw new AppErr('file1080 Err', 'Файл 1080 не найден');
}

@unlink($file1080) or
throw new AppErr('file1080 unlink err', 'Ошибка при удалении 1080');

if (!file_exists($fileOrig) || is_dir($fileOrig)){
    throw new AppErr('fileOrig Err', 'Файл Orig не найден');
}

@unlink($fileOrig) or
throw new AppErr('fileOrig unlink err', 'Ошибка при удалении Orig');

Response::success();