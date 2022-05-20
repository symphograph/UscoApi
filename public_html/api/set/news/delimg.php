<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
$User = User::byCheck();
$User->apiAuth(90);

$id = intval($_POST['id'] ?? 0);
if(!$id)
    die(json_encode(['error'=>'Ошибка данных']));

$img = $_POST['img'] ?? '';
if(empty($img) or !is_string($img))
    die(json_encode(['error'=>'Ошибка данных']));


$file1080 = $_SERVER['DOCUMENT_ROOT'].'/img/entry/1080/' . $id . '/' . $img;
$fileOrig = $_SERVER['DOCUMENT_ROOT'].'/img/entry/origins/' . $id . '/' . $img;
if(!file_exists($file1080) || is_dir($file1080))
    die(json_encode(['error'=>'Файл не найден']));
unlink($file1080);
unlink($fileOrig);
echo json_encode(['result'=>'Файл Удален']);


