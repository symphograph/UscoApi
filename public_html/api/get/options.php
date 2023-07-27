<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use App\DTO\HallDTO;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;

$Halls = HallDTO::getList() or
throw new AppErr('Halls is empty');

Response::data(['Halls' => $Halls]);
