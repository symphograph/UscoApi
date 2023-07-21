<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use App\{Announce, User};
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\Errors\ValidationErr;

User::auth([1, 2, 4]);


if (empty($_POST['evdata']))
    throw new ValidationErr('evdata');

$Announce = Announce::setByPost() or
throw new AppErr('Announce::setByPost err');
printr($Announce);
$Announce = \Symphograph\Bicycle\JsonDecoder::cloneFromAny($_POST['evdata'], Announce::class);
printr($Announce);
die();
$Announce->putToDB() or
throw new AppErr('putToDB err', 'Ошибка при сохранении');

Response::data($Announce);
