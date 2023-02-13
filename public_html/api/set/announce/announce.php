<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use App\{Anonce, APIusco, User};

$User = User::byCheck();
$User->apiAuth(needPowers: [1,2,4]);

if(empty($_POST['evdata']))
    die(APIusco::errorMsg('Ошибка запроса сервера'));

$Anonce = Anonce::setByPost() or
die(APIusco::errorMsg('Ошибка в полученных данных'));

$Anonce->putToDB() or
die(APIusco::errorMsg('Ошибка при сохранении'));

echo APIusco::resultData($Anonce);