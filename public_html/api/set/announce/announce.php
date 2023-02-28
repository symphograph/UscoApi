<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use App\{Anonce, User};
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\Errors\ValidationErr;

$User = User::byCheck();
$User->apiAuth(needPowers: [1, 2, 4]);

if (empty($_POST['evdata']))
    throw new ValidationErr('evdata');

$Anonce = Anonce::setByPost() or
throw new AppErr('Anonce::setByPost err');

$Anonce->putToDB() or
throw new AppErr('putToDB err', 'Ошибка при сохранении');

Response::data($Anonce);
