<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/includs/config.php';
$User = User::byCheck();
$User->apiAuth(90);

$id = intval($_POST['id'] ?? 0);
if (!$id)
    die(APIusco::errorMsg('Ошибка данных'));

$img = $_POST['img'] ?? '';
if (empty($img) or !is_string($img))
    die(APIusco::errorMsg('Ошибка данных'));


$file1080 = $_SERVER['DOCUMENT_ROOT'] . Entry::imgFolder . $id . '/' . $img;
$fileOrig = $_SERVER['DOCUMENT_ROOT'] . '/img/entry/origins/' . $id . '/' . $img;

if (!file_exists($file1080) || is_dir($file1080))
    die(APIusco::errorMsg('Файл не найден'));
@unlink($file1080)
or die(APIusco::errorMsg('Ошибка при удалении'));


if (!file_exists($fileOrig) || is_dir($fileOrig))
    die(APIusco::errorMsg('Файл не найден'));
@unlink($fileOrig)
or die(APIusco::errorMsg('Ошибка при удалении'));

echo APIusco::resultMsg();