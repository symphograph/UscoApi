<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use App\api\Api;
use App\Hall;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;

$Halls = Hall::getList() or
throw new AppErr('Halls');

Response::data(['Halls' => $Halls]);
