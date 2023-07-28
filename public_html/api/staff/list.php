<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use App\StaffGroup;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\Errors\ValidationErr;
use Symphograph\Bicycle\Helpers;

if(!Helpers::isDate($_POST['date'] ?? '')){
    throw new ValidationErr('date', 'Ошибка даты');
}

$StaffList = StaffGroup::getCollection($_POST['date']) or
throw new AppErr('StaffGroup::getCollection err', 'Группы не найдены');

Response::data($StaffList);