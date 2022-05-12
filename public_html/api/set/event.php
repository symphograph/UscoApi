<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
$User = User::byCheck();
$User->apiAuth(90);

if(empty($_POST['evdata']))
    die(json_encode(['error'=>'Ошибка запроса сервера']));
//var_dump($_POST['evdata']['show']);
//printr($_POST['evdata']); die();
if (!empty($_POST['addNew'])){
    $Anonce = Anonce::addNewAnonce() or
    die(json_encode(['error'=>'Ошибка при создании объекта']));
    die(json_encode($Anonce));
}

$Anonce = Anonce::setByPost() or
die(json_encode(['error'=>'Ошибка в полученных данных']));

$Anonce->putToDB() or
die(json_encode(['error'=>'Ошибка при сохранении']));