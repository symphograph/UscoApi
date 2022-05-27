<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/includs/config.php';
$User = User::byCheck();
$User->apiAuth(90);

if(empty($_POST['evdata']))
    die(APIusco::errorMsg('Ошибка запроса сервера'));

$Anonce = Anonce::setByPost() or
die(APIusco::errorMsg('Ошибка в полученных данных'));

$Anonce->putToDB() or
die(APIusco::errorMsg('Ошибка при сохранении'));

echo APIusco::resultData($Anonce);