<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/includs/config.php';
$User = User::byCheck();
$User->apiAuth(90);

$id = $_POST['id'] ?? 0;
if(!$id)
    die(json_encode(['error'=>'Ошибка данных']));

$qwe = Anonce::delete($_POST['id']);
if (!$qwe){
    die(json_encode(['error'=>'Ошибка данных']));
}
echo json_encode(['result'=>'Ok']);