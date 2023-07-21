<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use App\{Announce, User};
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;

User::auth([1, 2, 4]);

$Announce = Announce::addNewAnnounce() or
throw new AppErr('addNewAnnounce err');

Response::data($Announce);
