<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use App\{Announce, User};
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\Errors\ValidationErr;
use Symphograph\Bicycle\JsonDecoder;

User::auth([1, 2, 4]);


if (empty($_POST['announce']))
    throw new ValidationErr('announce is empty');

$Announce = Announce::setByPost() or
throw new AppErr('Announce::setByPost err');

/** @var Announce $Announce */
$Announce = JsonDecoder::cloneFromAny($_POST['announce'], Announce::class);
$Announce->putToDB();

Response::data($Announce);
