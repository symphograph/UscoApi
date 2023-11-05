<?php
require_once dirname(__DIR__, 3) . '/vendor/autoload.php';

use App\DTO\HallDTO;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;

$Halls = HallDTO::getList() or
throw new AppErr('Halls is empty');

Response::data(['Halls' => $Halls]);
